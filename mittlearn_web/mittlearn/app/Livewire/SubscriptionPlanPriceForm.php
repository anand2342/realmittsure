<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SubscriptionPlanPriceForm extends Component
{
    public $priceRows = [];
    public $priceCols = ['id'=> 0, 'plan_id'=> 0, 'duration_type'=> '', 'duration_days'=> '', 'price'=> '', 'discount_type'=> 'flat', 'discount_value'=> '', 'final_price'=> 0];
    public $durationTypesList = [];
    public $durationTypesListUpdated = [];
    public $planData = null;
    public $isDisableAddMoreBtn = 0;


    public function mount($plan_data)
    {
        $this->planData = $plan_data;
        $this->priceRows[] = $this->priceCols;
        $this->durationTypesList = getDurationTypeList();
        $this->durationTypesListUpdated = $this->durationTypesList;

        if ($this->planData) {
            if ($this->planData->subscriptionPlanPrice) {
                $priceRowsArr = [];
                foreach($this->planData->subscriptionPlanPrice as $val) {
                    $finalPrice = calculatePlanFinalPrice($val);
                    $priceRowsArr[] = [
                        'id'=> $val->id,
                        'plan_id'=> $val->plan_id,
                        'duration_type'=> $val->duration_type,
                        'duration_days'=> $val->duration_days,
                        'price'=> $val->price,
                        'discount_type'=> $val->discount_type ? $val->discount_type : 'flat',
                        'discount_value'=> $val->discount_value,
                        'final_price'=> $finalPrice
                    ];
                }
                $this->priceRows = $priceRowsArr;
            }
        }
    }
    
    public function addRow()
    {
        $this->priceRows[] = $this->priceCols;
        //$this->updateDurationTypesList();
        $this->updateIsDisableAddMoreBtn();
    }

    public function removeRow($index)
    {
        // Remove the row at the specified index
        unset($this->priceRows[$index]);
        $this->priceRows = array_values($this->priceRows); // Re-index the array
        //$this->updateDurationTypesList();
        $this->updateIsDisableAddMoreBtn();
    }

    public function onChangeFiledValue($value, $index, $fieldName){
        $this->priceRows[$index][$fieldName]  = $value ?? 0;

        $priceRowData = $this->priceRows[$index];

        $priceRowData['final_price'] = calculatePlanFinalPrice($priceRowData);
        //assign updated values
        $this->priceRows[$index] = $priceRowData;
    }
    
    public function render()
    {
        return view('livewire.subscription-plan-price-form');
    }

    //
    public function updateDurationTypesList() {
        // Extract duration types from priceRows
        $existingDurations = array_column($this->priceRows, 'duration_type');
        Log::info(['existingDurations'=> $existingDurations]);
        // Filter durationTypesList to exclude existing durations
        $filteredDurationTypes = array_filter($this->durationTypesList, function ($value, $key) use ($existingDurations) {
            Log::info(['value'=>$value, 'key'=>$key, 'inA'=>!in_array($key, $existingDurations)]);
            return !in_array($key, $existingDurations);
        }, ARRAY_FILTER_USE_BOTH);
        $this->durationTypesListUpdated = $filteredDurationTypes;
       // Log::info(['filteredDurationTypes'=> $this->durationTypesListUpdated, 'existingDurations'=> $existingDurations, 'rows'=>$this->priceRows]);
    }

    function updateIsDisableAddMoreBtn() {
        $this->isDisableAddMoreBtn = count($this->durationTypesList) == count($this->priceRows) ? 1 : 0;
    }
}
