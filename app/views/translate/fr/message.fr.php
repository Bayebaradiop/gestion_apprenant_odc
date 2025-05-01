<?php
require_once __DIR__ . '/../../../enums/message.enum.php';

use App\ENUM\MESSAGE\MSGENUM;

$message = [
    // Messages généraux
    MSGENUM::REUSSI->value => "Opération réussie.",

    // Messages liés aux promotions
    MSGENUM::PROMO_AJOUT_REUSSI->value => "La promotion a été ajoutée avec succès.",
    MSGENUM::PROMO_ACTIVATION_REUSSIE->value => "La promotion a été activée avec succès.",

];
