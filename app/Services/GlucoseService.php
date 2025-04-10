<?php

namespace App\Services;

use App\Models\Glucose;
use App\Jobs\GlucoseReportJob;
use App\Models\GlucoseDay;
use App\Repositories\Contracts\GlucoseRepositoryInterface;
use App\Repositories\GlucoseDayRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    public function createUpdate(array $data): array
    {
        $result = [];

        foreach ($data as $item) {
            $result[] = $this->glucoseRepository->createUpdateGlucose($item);
        }

        return $result;
    }

    public function delete(string | int $id): ?bool
    {
        return $this->glucoseRepository->deleteGlucose($id);
    }

    public function exportGlucose(array $data)
    {
        $glucoses = $this->glucoseDayRepository->getAll($data, false);

        $startDate = $this->carbon->parse($data['period_start']);
        $endDate = $this->carbon->parse($data['period_final']);
        $currentMonth = $startDate->copy()->startOfMonth();

        $pdfContent = [];

        while ($currentMonth->lte($endDate)) {
            $monthStr = $currentMonth->format('Y-m');
            $monthLabel = $currentMonth->format('m/Y');

            // Filtrar os dados apenas do mês atual
            $monthlyGlucoses = $glucoses->filter(function ($item) use ($monthStr) {
                return $this->carbon->parse($item->date)->format('Y-m') === $monthStr;
            });

            if ($monthlyGlucoses->isNotEmpty()) {
                // Dias do mês atual (1 a 31)
                $daysInMonth = range(1, 31);

                $groupedGlucoses = collect($daysInMonth)->mapWithKeys(function ($day) use ($monthlyGlucoses, $currentMonth, $startDate, $endDate) {
                    // Monta a data completa do dia em loop
                    $date = $currentMonth->copy()->day($day);

                    // Se a data for fora do intervalo real, retorna vazio
                    if ($date->lt($startDate) || $date->gt($endDate)) {
                        return [$day => [
                            'basal' => '',
                            'meals' => collect(),
                        ]];
                    }

                    // Se estiver no range, monta normalmente
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

                // Gera a view do mês
                $pdfContent[] = view('pdf.glucose', compact('groupedGlucoses', 'monthLabel'))->render();
            }

            $currentMonth->addMonthNoOverflow();
        }

        if (empty($pdfContent)) {
            throw new \Exception('Não há dados para o período selecionado!');
        }

        // Gerar o PDF com todas as páginas
        $pdf = Pdf::loadHTML(implode($pdfContent))
            ->setPaper('a4', 'landscape')
            ->setOption('isRemoteEnabled', true);

        $filePath = '/tmp/glucose_report_' . auth()->user()->id . '.pdf';
        file_put_contents($filePath, $pdf->output());

        // $filePath = 'reports/glucose_report/' . auth()->user()->id . '.pdf';
        // Storage::disk('local')->put($filePath, $pdf->output());

        if (!empty($data['email'])) {
            $textPeriod = "Período de " . formatDateBr($data['period_start']) . " até " . formatDateBr($data['period_final']);
            GlucoseReportJob::dispatch($textPeriod, $filePath, auth()->user()->email);
            return response()->json(['message' => 'E-mail enviado com sucesso.']);
        }

        //return response()->file(storage_path('app/' . $filePath));
        return response()->file($filePath);
    }
}
