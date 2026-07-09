<?php

namespace App\Livewire;

use Livewire\Component;

class SubscriptionPlanPackForm extends Component
{
    public $packRows = [];
    public $packCols = [
        'id' => 0,
        'plan_id' => 0,
        'pack_type' => '',
        'set_of_courses' => '',
        'discount_type' => 'flat',
        'discount_value' => '',
        'free_course_academic' => '',
        'free_course_non_academic' => '',
    ];

    public $planData = null;
    public $isDisableAddMoreBtn = 0;
    public $packType = 'pack_of_courses';

    public function mount($plan_data)
    {
        $this->planData = $plan_data;
        $this->packRows[] = $this->packCols;

        if ($this->planData) {

            if ($this->planData->subscriptionPlanPack) {
                $packRowsArr = [];
                foreach ($this->planData->subscriptionPlanPack as $val) {
                    $packRowsArr[] = [
                        'id' => $val->id,
                        'plan_id' => $val->plan_id,
                        'set_of_courses' => $val->set_of_courses,
                        'pack_type' => $val->pack_type,
                        'discount_type' => $val->discount_type ? $val->discount_type : 'flat',
                        'discount_value' => $val->discount_value,
                        'free_course_academic' => $val->free_course_academic,
                        'free_course_non_academic' => $val->free_course_non_academic,
                    ];
                }
                $this->packRows = $packRowsArr;
            }
        }
    }

    public function setPackType($type)
    {
        $this->packType = $type;
    }

    public function addRow()
    {
        $this->packRows[] = $this->packCols;
        
    }

    public function removeRow($index)
    {
        unset($this->packRows[$index]);
    }

    public function render()
    {
        return view('livewire.subscription-plan-pack-form');
    }
}
