<?php

namespace App\Manager;

use App\Entity\Asset;

interface AssetManagerInterface {
    public function calculateInvestment(Asset $asset): void;
}
