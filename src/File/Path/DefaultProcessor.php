<?php
namespace App\File\Path;

use Cake\ORM\Entity;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Josegonzalez\Upload\File\Path\Basepath\DefaultTrait as BasepathTrait;
use Josegonzalez\Upload\File\Path\Filename\DefaultTrait as FilenameTrait;
use Josegonzalez\Upload\File\Path\ProcessorInterface;
use Symfony\Component\Console\Exception\LogicException;

class DefaultProcessor implements ProcessorInterface
{
    /**
     * Table instance.
     *
     * @var \Cake\ORM\Table
     */
    protected $table;

    /**
     * Entity instance.
     *
     * @var \Cake\ORM\Entity
     */
    protected $entity;

    /**
     * Array of uploaded data for this field
     *
     * @var array
     */
    protected $data;

    /**
     * Name of field
     *
     * @var string
     */
    protected $field;

    /**
     * Settings for processing a path
     *
     * @var array
     */
    protected $settings;

    /**
     * Constructor
     *
     * @param \Cake\ORM\Table  $table the instance managing the entity
     * @param \Cake\ORM\Entity $entity the entity to construct a path for.
     * @param array            $data the data being submitted for a save
     * @param string           $field the field for which data will be saved
     * @param array            $settings the settings for the current field
     */
    public function __construct(Table $table, Entity $entity, $data, $field, $settings)
    {
        $this->table = $table;
        $this->entity = $entity;
        $this->data = $data;
        $this->field = $field;
        $this->settings = $settings;
    }

    use BasepathTrait;

    public function filename()
    {
        $extension = explode(".", $this->data['name']);
        return time().'.'.end($extension);
    }

    public function basepath()
    {
        $defaultPath = 'webroot{DS}files{DS}{model}{DS}{field}{DS}';
        $path = Hash::get($this->settings, 'path', $defaultPath);
        if (strpos($path, '{primaryKey}') !== false) {
            if ($this->entity->isNew()) {
                throw new LogicException('{primaryKey} substitution not allowed for new entities');
            }
            if (is_array($this->table->primaryKey())) {
                throw new LogicException('{primaryKey} substitution not valid for composite primary keys');
            }
        }

        $replacements = [
            '{primaryKey}' => $this->entity->get($this->table->primaryKey()),
            '{model}' => $this->table->alias(),
            '{table}' => $this->table->table(),
            '{field}' => $this->field,
            '{year}' => date("Y"),
            '{month}' => date("m"),
            '{day}' => date("d"),
            '{time}' => time(),
            '{microtime}' => microtime(),
            '{DS}' => DIRECTORY_SEPARATOR,
            '{campaign}' => $this->entity->get('campaign_id'),
        ];

        if (preg_match_all("/{field-value:(\w+)}/", $path, $matches)) {
            foreach ($matches[1] as $field) {
                $value = $this->entity->get($field);
                if ($value === null) {
                    throw new LogicException(sprintf('Field value for substitution is missing: %s', $field));
                } elseif (!is_scalar($value)) {
                    throw new LogicException(sprintf('Field value for substitution must be a integer, float, string or boolean: %s', $field));
                } elseif (strlen($value) < 1) {
                    throw new LogicException(sprintf('Field value for substitution must be non-zero in length: %s', $field));
                }

                $replacements[sprintf('{field-value:%s}', $field)] = $value;
            }
        }

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $path
        );
    }
}
