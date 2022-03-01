<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\CurrentUserTrait;

/**
 * Settings Model
 *
 * @property \Cake\ORM\Association\BelongsTo $SettingCategories
 *
 * @method \App\Model\Entity\Setting get($primaryKey, $options = [])
 * @method \App\Model\Entity\Setting newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Setting[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Setting|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Setting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Setting[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Setting findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 *
 * @var  $setting Setting
 */
class SettingsTable extends Table
{
    use CurrentUserTrait;

    /**
     * GetListTypes method
     * @return array
     */
    function getListTypes(){
        return [
            'text' => 'text',
            'textarea' => 'textarea',
            'select' => 'select',
            'checkbox' => 'checkbox',
            'radio' => 'radio',
            'password' => 'password',
            'file' => 'file'
        ];
    }
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('settings');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('BeforeDelete');
        $this->addBehavior('Auditable');

        $this->belongsTo('SettingCategories', [
            'foreignKey' => 'setting_category_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('value', 'create')
            ->notEmpty('value');

        $validator
            ->requirePresence('label', 'create')
            ->notEmpty('label');

        $validator
            ->allowEmpty('description');

        $validator
            ->requirePresence('type_key', 'create')
            ->notEmpty('type_key');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['name', 'setting_category_id']));
        $rules->add($rules->existsIn(['setting_category_id'], 'SettingCategories'));

        return $rules;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @return array
     */


    function getKeyValuePairs(){
        $settings = $this->find()
            ->contain(['SettingCategories'])
            ->order(['SettingCategories.name' => 'ASC', 'Settings.name' => 'ASC'])
            ->all();
        $setting_key_value_pairs = [];
        foreach ($settings as $setting){
            $setting_key_value_pairs[$setting->setting_category->name][$setting->name] = $setting->value;
            # $setting_key_value_pairs[$variable['SettingCategory']['name'].'.'.$variable['Setting']['name']] = $variable['Setting']['value'];
        }
        return $setting_key_value_pairs;
    }
}
