<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\List\TransactionListType;
use Jeandanyel\CrudBundle\Attribute\CrudController;
use Jeandanyel\CrudBundle\Controller\AbstractCrudController;

#[CrudController(entityClass: Transaction::class, formTypeClass: TransactionType::class, listTypeClass: TransactionListType::class)]
class TransactionController extends AbstractCrudController
{
}
