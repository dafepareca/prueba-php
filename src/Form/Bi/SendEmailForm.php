<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 9/05/17
 * Time: 12:30 PM
 */

namespace App\Form\Bi;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class SendEmailForm extends Form
{

    protected function _buildSchema(Schema $schema)
    {
        return $schema->addField('Subject', 'string')
            ->addField('Message', ['type' => 'string']);
    }

    protected function _buildValidator(Validator $validator)
    {
        return $validator->add('Subject', 'not-blank', ['rule' => 'notBlank'])
                        ->requirePresence('Subject')
                        ->notEmpty('Subject')

                        ->add('Message', 'not-blank', ['rule' => 'notBlank'])
                        ->requirePresence('Message')
                        ->notEmpty('Message');
    }

    protected function _execute(array $data)
    {
        // Send an email.
        return true;
    }
}