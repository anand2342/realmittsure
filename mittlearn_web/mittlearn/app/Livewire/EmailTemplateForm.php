<?php

namespace App\Livewire;

use App\Models\EmailAction;
use App\Models\AlertTemplate;
use Livewire\Component;

class EmailTemplateForm extends Component
{
    public $actionOptions = [];
    public $selectedAction = null;
    public $options = [];
    public $selectedConstant = null;
    public $body = '';
    public $emailTemplateId = null;
    public $type;

    public function mount($emailTemplateId = null)
    {
        $this->actionOptions = EmailAction::pluck('action', 'id')->toArray();

        if ($emailTemplateId) {
            $template = AlertTemplate::findOrFail($emailTemplateId);
            $this->emailTemplateId = $template->id;
            $this->selectedAction = $template->action;
            $this->body = $template->body;

            $this->loadConstants($this->selectedAction);
        }
    }

    public function loadConstants($actionId)
    {
        $this->selectedAction = $actionId;
        $action = EmailAction::find($actionId);
        if ($action) {
            $this->options = [];
            $optionsArray = explode(',', $action->options);

            foreach ($optionsArray as $option) {
                $this->options[trim($option)] = trim($option);
            }
        } else {
            $this->options = [];
        }
    }
    public function insertConstant()
    {
        if ($this->selectedConstant) {
            $this->body .= "{" . $this->selectedConstant . "}";
        }
    }

    public function render()
    {
        return view('livewire.email-template-form', [
            'actionOptions' => $this->actionOptions,
            'options' => $this->options,
        ]);
    }
}
