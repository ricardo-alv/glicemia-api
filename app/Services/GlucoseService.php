<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Glucose;
use App\Jobs\GlucoseReportJob;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Repositories\GlucoseDayRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\Contracts\GlucoseRepositoryInterface;

class GlucoseService
{
    public function __construct(
        protected  GlucoseRepositoryInterface $glucoseRepository,
        protected GlucoseDayRepository $glucoseDayRepository,
        protected Carbon $carbon
    ) {}

    public function getAll(array $data): Collection | LengthAwarePaginator
    {
        return $this->glucoseRepository->getAll($data);
    }

    public function show(string | int $id): ?Glucose
    {
        return $this->glucoseRepository->show($id);
    }

    public function create(array $data): Glucose
    {
        return $this->glucoseRepository->create($data);
    }

    public function update(array $data, string | int $id): ?Glucose
    {
        return $this->glucoseRepository->update($data, $id);
    }

    public function delete(string | int $id): ?bool
    {
        return $this->glucoseRepository->delete($id);
    }

    public function exportGlucose(array $data)
    {
        $glucoses = $this->glucoseDayRepository->getAll($data, false);
        $startDate = $this->carbon->parse($data['period_start']);
        $endDate = $this->carbon->parse($data['period_final']);
        $pdfContent = $this->buildPdfContent($glucoses, $startDate, $endDate);

        if (empty($pdfContent)) {
            throw new \Exception('Não há dados para o período selecionado!');
        }

        $pdf = $this->generatePdf($pdfContent);
        $filePath = $this->storePdf($pdf);

        if (!empty($data['email'])) {
            $this->sendReportByEmail($data, $filePath);
            return response()->json(['message' => 'E-mail enviado com sucesso.']);
        }

        return response()->file($filePath);
    }


    private function buildPdfContent($glucoses, $startDate, $endDate): array
    {
        $pdfContent = [];
        $currentMonth = $startDate->copy()->startOfMonth();

        while ($currentMonth->lte($endDate)) {
            $monthStr = $currentMonth->format('Y-m');
            $monthLabel = $currentMonth->format('m/Y');

            $monthlyGlucoses = $glucoses->filter(function ($item) use ($monthStr) {
                return $this->carbon->parse($item->date)->format('Y-m') === $monthStr;
            });

            if ($monthlyGlucoses->isNotEmpty()) {
                $groupedGlucoses = $this->groupGlucosesByDay($monthlyGlucoses, $currentMonth, $startDate, $endDate);
                $pdfContent[] = view('pdf.glucose', compact('groupedGlucoses', 'monthLabel'))->render();
            }

            $currentMonth->addMonthNoOverflow();
        }

        return $pdfContent;
    }

    private function groupGlucosesByDay($monthlyGlucoses, $currentMonth, $startDate, $endDate)
    {
        $daysInMonth = range(1, 31);

        return collect($daysInMonth)->mapWithKeys(function ($day) use ($monthlyGlucoses, $currentMonth, $startDate, $endDate) {
            $date = $currentMonth->copy()->day($day);

            if ($date->lt($startDate) || $date->gt($endDate)) {
                return [$day => ['basal' => '', 'meals' => collect()]];
            }

            $glucoseDay = $monthlyGlucoses->first(function ($item) use ($date) {
                return $this->carbon->parse($item->date)->isSameDay($date);
            });

            return [$day => [
                'basal' => $glucoseDay->basal ?? '',
                'meals' => $glucoseDay && isset($glucoseDay->glucoses)
                    ? $glucoseDay->glucoses->groupBy('mealType.name')
                    : collect(),
            ]];
        });
    }

    private function generatePdf(array $pdfContent)
    {
        return Pdf::loadHTML(implode($pdfContent))
            ->setPaper('a4', 'landscape')
            ->setOption('isRemoteEnabled', true);
    }

    private function storePdf($pdf): string
    {
        $filePath = '/tmp/glucose_report_' . auth()->user()->id . '.pdf';
        file_put_contents($filePath, $pdf->output());
        return $filePath;
    }

    private function sendReportByEmail(array $data, string $filePath): void
    {
        $textPeriod = "Período de " . formatDateBr($data['period_start']) . " até " . formatDateBr($data['period_final']);
        GlucoseReportJob::dispatch($textPeriod, $filePath, auth()->user()->email);
    }
}
