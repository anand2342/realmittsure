<?php

namespace App\Livewire;

use Livewire\Component;

class SubscriptionPlanFeaturesForm extends Component
{
    public $featureRows = [];
    public $featureCols = ['id' => 0, 'plan_id' => 0, 'title' => ''];
    public $planData = null;
    public $isDisableAddMoreBtn = 0;


    public function mount($plan_data)
    {
        $this->planData = $plan_data;
        $this->featureRows[] = $this->featureCols;
        if ($this->planData) {
            if ($this->planData->subscriptionPlanFeature) {
                $featureRowsArr = [];
                foreach ($this->planData->subscriptionPlanFeature as $val) {
                    $featureRowsArr[] = ['id' => $val->id, 'plan_id' => $val->plan_id, 'title' => $val->title];
                }
                $this->featureRows = $featureRowsArr;
            }
        }
    }

    public function addRow()
    {
        $this->featureRows[] = $this->featureCols;
        $this->updateIsDisableAddMoreBtn();
    }

    public function removeRow($index)
    {
        // Remove the row at the specified index
        unset($this->featureRows[$index]);
        $this->featureRows = array_values($this->featureRows); // Re-index the array
        //$this->updateDurationTypesList();
        $this->updateIsDisableAddMoreBtn();
    }

    public function onChangeFiledValue($value, $index, $fieldName)
    {
        $this->featureRows[$index][$fieldName]  = $value ?? 0;
    }

    public function render()
    {
        return view('livewire.subscription-plan-features-form');
    }

    function updateIsDisableAddMoreBtn()
    {
        $this->isDisableAddMoreBtn = count($this->featureRows) > 10 ? 1 : 0;
    }
}
