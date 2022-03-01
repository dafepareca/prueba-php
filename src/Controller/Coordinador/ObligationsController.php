<?php
namespace App\Controller\Coordinador;

use App\Controller\AppController;
use App\Controller\BaseObligationsController;
use App\Model\Entity\AdjustedObligation;
use App\Model\Entity\Customer;
use App\Model\Entity\HistoryStatus;
use App\Model\Entity\Obligation;
use App\Model\Table\AccessTypesActivitiesTable;
use App\Model\Table\AdjustedObligationsTable;
use App\Model\Table\CityOfficesTable;
use App\Model\Table\HistoryStatusesTable;
use App\Model\Table\ObligationsTable;
use App\Model\Table\RolesTable;
use App\Model\Table\TypeObligationsTable;
use App\Utility\Pdf;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Database\Exception;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\View\View;
use Psy\Util\Json;

/**
 * Customers Controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\ObligationsTable $Obligations
 * @property \App\Model\Table\CityOfficesTable $CityOffices
 * @property \App\Model\Table\CustomerTypeIdentificationsTable $CustomerTypeIdentifications
 * @property \App\Model\Table\AdjustedObligationsTable $AdjustedObligations
 * @property \App\Model\Table\CommitteesTable $Committees
 * @property \App\Controller\Component\DaviviendaComponent $Davivienda
 * @property \App\Model\Table\HistoryCustomersTable $HistoryCustomers
 * @property \App\Model\Table\QueriesCustomersTable $QueriesCustomers
 *
 */
class ObligationsController extends BaseObligationsController
{

}

