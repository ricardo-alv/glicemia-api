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

    public function createUpdate(array $data): Glucose
    {
        return $this->glucoseRepository->createUpdateGlucose($data);
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

        $pdfContent = [];

        $currentMonth = $startDate->copy()->startOfMonth();

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

        // while ($startDate->lte($endDate)) {    
        //     // Obtém o mês atual no formato 'Y-m'
        //     $currentMonth = $startDate->format('Y-m');
        //     $currentMonthFormat = $startDate->format('m/Y');

        //     // Filtra os dados para o mês atual
        //     $monthlyGlucoses = $glucoses->filter(function ($item) use ($startDate, $currentMonth) {
        //         return $this->carbon->parse($item->date)->format('Y-m') === $currentMonth;
        //     });

        //     Log::info("Qtd monthlyGlucoses para $currentMonth: " . $monthlyGlucoses->count());

        //     // Se houver dados para o mês, cria uma página no PDF
        //     if ($monthlyGlucoses->isNotEmpty()) {
        //         // Organize os dados do mês
        //         $daysInMonth = range(1, 31);  // Pode ser ajustado conforme necessário

        //         $groupedGlucoses = collect($daysInMonth)->mapWithKeys(function ($day) use ($monthlyGlucoses) {
        //             $glucoseDay = $monthlyGlucoses->filter(function ($item) use ($day) {
        //                 return $this->carbon->parse($item->date)->day == $day;
        //             });

        //             return [$day => [
        //                 'basal' => $glucoseDay->first()->basal ?? '',
        //                 'meals' => $glucoseDay->isNotEmpty() ? $glucoseDay->first()->glucoses->groupBy('mealType.name') : collect(),
        //             ]];
        //         });

        //         // Adiciona os dados do mês ao conteúdo do PDF
        //         $pdfContent[] = view('pdf.glucose', compact('groupedGlucoses', 'currentMonthFormat'))->render();
        //     }

        //     // Avançar para o próximo mês
        //     $startDate->addMonthNoOverflow();
        // }

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
