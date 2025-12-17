<?php

namespace App\Services;

/**
 * Laravel сервис для сортировки массивов
 * Миграция из Practice 7 с адаптацией под Laravel
 */
class SortService
{
    private int $comparisons = 0;
    private int $swaps = 0;

    /**
     * Сортировка массива выбранным алгоритмом
     */
    public function sort(string $input, string $algorithm): array
    {
        // Парсим входную строку в массив
        $array = $this->parseInput($input);
        
        // Выполняем сортировку
        $startTime = microtime(true);
        $sortedArray = $this->applySortingAlgorithm($array, $algorithm);
        $endTime = microtime(true);
        
        return [
            'original' => $array,
            'sorted' => $sortedArray,
            'algorithm' => $algorithm,
            'execution_time' => round(($endTime - $startTime) * 1000, 3), // в миллисекундах
            'comparisons' => $this->getComparisonCount(),
            'swaps' => $this->getSwapCount(),
        ];
    }

    /**
     * Получение доступных алгоритмов сортировки
     */
    public function getAvailableAlgorithms(): array
    {
        return [
            'merge' => 'Сортировка слиянием (Merge Sort)',
            'quick' => 'Быстрая сортировка (Quick Sort)',
            'bubble' => 'Пузырьковая сортировка (Bubble Sort)',
            'insertion' => 'Сортировка вставками (Insertion Sort)',
            'selection' => 'Сортировка выбором (Selection Sort)',
            'heap' => 'Пирамидальная сортировка (Heap Sort)',
        ];
    }

    /**
     * Парсинг входной строки в массив чисел
     */
    private function parseInput(string $input): array
    {
        // Удаляем лишние пробелы и разделяем по запятым или пробелам
        $input = trim($input);
        
        if (empty($input)) {
            throw new \InvalidArgumentException('Массив не может быть пустым');
        }

        // Поддерживаем разные форматы: "1,2,3" или "1 2 3" или "[1,2,3]"
        $input = preg_replace('/[\[\]]/', '', $input); // Удаляем скобки
        $elements = preg_split('/[,\s]+/', $input, -1, PREG_SPLIT_NO_EMPTY);
        
        $array = [];
        foreach ($elements as $element) {
            if (!is_numeric($element)) {
                throw new \InvalidArgumentException("Элемент '{$element}' не является числом");
            }
            $array[] = (float) $element;
        }

        if (count($array) > 1000) {
            throw new \InvalidArgumentException('Массив слишком большой (максимум 1000 элементов)');
        }

        return $array;
    }

    /**
     * Применение алгоритма сортировки
     */
    private function applySortingAlgorithm(array $array, string $algorithm): array
    {
        $this->comparisons = 0;
        $this->swaps = 0;

        return match($algorithm) {
            'merge' => $this->mergeSort($array),
            'quick' => $this->quickSort($array),
            'bubble' => $this->bubbleSort($array),
            'insertion' => $this->insertionSort($array),
            'selection' => $this->selectionSort($array),
            'heap' => $this->heapSort($array),
            default => throw new \InvalidArgumentException("Неизвестный алгоритм: {$algorithm}"),
        };
    }

    // Все алгоритмы сортировки (аналогично Practice 7)
    private function mergeSort(array $array): array
    {
        if (count($array) <= 1) {
            return $array;
        }

        $middle = (int) (count($array) / 2);
        $left = array_slice($array, 0, $middle);
        $right = array_slice($array, $middle);

        $left = $this->mergeSort($left);
        $right = $this->mergeSort($right);

        return $this->merge($left, $right);
    }

    private function merge(array $left, array $right): array
    {
        $result = [];
        $i = $j = 0;

        while ($i < count($left) && $j < count($right)) {
            $this->comparisons++;
            if ($left[$i] <= $right[$j]) {
                $result[] = $left[$i++];
            } else {
                $result[] = $right[$j++];
            }
        }

        while ($i < count($left)) {
            $result[] = $left[$i++];
        }

        while ($j < count($right)) {
            $result[] = $right[$j++];
        }

        return $result;
    }

    private function quickSort(array $array): array
    {
        if (count($array) <= 1) {
            return $array;
        }

        $pivot = $array[0];
        $less = $greater = [];

        for ($i = 1; $i < count($array); $i++) {
            $this->comparisons++;
            if ($array[$i] <= $pivot) {
                $less[] = $array[$i];
            } else {
                $greater[] = $array[$i];
            }
        }

        return array_merge(
            $this->quickSort($less),
            [$pivot],
            $this->quickSort($greater)
        );
    }

    private function bubbleSort(array $array): array
    {
        $n = count($array);
        
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                $this->comparisons++;
                if ($array[$j] > $array[$j + 1]) {
                    $this->swaps++;
                    [$array[$j], $array[$j + 1]] = [$array[$j + 1], $array[$j]];
                }
            }
        }

        return $array;
    }

    private function insertionSort(array $array): array
    {
        $n = count($array);
        
        for ($i = 1; $i < $n; $i++) {
            $key = $array[$i];
            $j = $i - 1;

            while ($j >= 0) {
                $this->comparisons++;
                if ($array[$j] > $key) {
                    $this->swaps++;
                    $array[$j + 1] = $array[$j];
                    $j--;
                } else {
                    break;
                }
            }
            $array[$j + 1] = $key;
        }

        return $array;
    }

    private function selectionSort(array $array): array
    {
        $n = count($array);
        
        for ($i = 0; $i < $n - 1; $i++) {
            $minIdx = $i;
            
            for ($j = $i + 1; $j < $n; $j++) {
                $this->comparisons++;
                if ($array[$j] < $array[$minIdx]) {
                    $minIdx = $j;
                }
            }
            
            if ($minIdx !== $i) {
                $this->swaps++;
                [$array[$i], $array[$minIdx]] = [$array[$minIdx], $array[$i]];
            }
        }

        return $array;
    }

    private function heapSort(array $array): array
    {
        $n = count($array);

        // Построение кучи
        for ($i = (int)($n / 2) - 1; $i >= 0; $i--) {
            $this->heapify($array, $n, $i);
        }

        // Извлечение элементов из кучи
        for ($i = $n - 1; $i > 0; $i--) {
            $this->swaps++;
            [$array[0], $array[$i]] = [$array[$i], $array[0]];
            $this->heapify($array, $i, 0);
        }

        return $array;
    }

    private function heapify(array &$array, int $n, int $i): void
    {
        $largest = $i;
        $left = 2 * $i + 1;
        $right = 2 * $i + 2;

        if ($left < $n) {
            $this->comparisons++;
            if ($array[$left] > $array[$largest]) {
                $largest = $left;
            }
        }

        if ($right < $n) {
            $this->comparisons++;
            if ($array[$right] > $array[$largest]) {
                $largest = $right;
            }
        }

        if ($largest !== $i) {
            $this->swaps++;
            [$array[$i], $array[$largest]] = [$array[$largest], $array[$i]];
            $this->heapify($array, $n, $largest);
        }
    }

    private function getComparisonCount(): int
    {
        return $this->comparisons;
    }

    private function getSwapCount(): int
    {
        return $this->swaps;
    }
}