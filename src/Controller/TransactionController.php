<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionType;
use Jeandanyel\CrudBundle\Attribute\CrudController;
use Jeandanyel\CrudBundle\Controller\AbstractCrudController;

#[CrudController(entityClass: Transaction::class, formTypeClass: TransactionType::class)]
class TransactionController extends AbstractCrudController
{
}
