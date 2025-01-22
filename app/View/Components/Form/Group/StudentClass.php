<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;
use App\Models\Common\StudentClass as Model;
class StudentClass extends Form
{
    public $type = 'class';

    public $path;

    public $remoteAction;

    public $field;

    public $student_class;

    public $currency;

    public $change;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        if (empty($this->name)) {
            $this->name = 'student_class';
        }

        // $this->path = route('modals.student_class.create');
        $this->remoteAction = route('studentclasses.index', ['search' => 'enabled:1']);

        $this->field = [
            'key' => 'id',
            'value' => 'name'
        ];

        $this->student_class = Model::orderBy('name')->get();

        $model = $this->getParentData('model');

        $class_id = old('student_class', old('student_class.id', old('student_class', null)));

        if (! empty($class_id)) {
            $this->selected = ($this->multiple) ? (array) $class_id : $class_id;

            foreach ($class_id as $id) {
                if (! $this->student_class->has($id)) {
                    $class = Model::find($id);
                    $this->student_class->put($class->id, $class->name);
                }
            }
        }

        if (! empty($model) && ! empty($model->{$this->name})) {
            $this->selected = $model->{$this->name};
        }
        if ($this->selected === null && $this->multiple) {
            $this->selected = (setting('default.student_class')) ? [setting('default.student_class')] : null;
        } else if ($this->selected === null) {
            $this->selected = setting('default.student_class', null);
        }
        return view('components.form.group.student_class');
    }
}
