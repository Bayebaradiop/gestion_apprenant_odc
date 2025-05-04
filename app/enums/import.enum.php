<?php


enum IMPORTMETHODE: string
{
    case EMAIL_UNIQUE = 'email_unique';
    case MATRICULE_UNIQUE = 'matricule_unique';
    case COLONNE_NON_VIDE = 'colonne_non_vide';
    case FICHIER_VALID = 'fichier_valid';
}