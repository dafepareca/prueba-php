<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Certificates Model
 *
 * @property \App\Model\Table\NormalizationsHasCertificatesTable|\Cake\ORM\Association\HasMany $NormalizationsHasCertificates
 *
 * @method \App\Model\Entity\Certificate get($primaryKey, $options = [])
 * @method \App\Model\Entity\Certificate newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Certificate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Certificate|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Certificate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Certificate[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Certificate findOrCreate($search, callable $callback = null, $options = [])
 */
class CertificatesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('certificates');
        $this->setDisplayField('certificate');
        $this->setPrimaryKey('id');

        $this->hasMany('NormalizationsHasCertificates', [
            'foreignKey' => 'certificate_id'
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
            ->requirePresence('certificate', 'create')
            ->notEmpty('certificate');

        return $validator;
    }
}
