<?php
namespace Annoncea\Model;

/*
 * sert à verifier que l'on a un seul objet représentant chaque table (genre de singleton)
 */

class BaseAnnoncea{
    
    public static $annonceTable;
    public static $favorisTable;
    public static $photoTable;
    public static $departementTable; 
    public static $categorieTable;
    public static $utilisateurTable;
    public static $regionTable;
    
    public static function getAnnonceTable($sm)
    {
        if (!self::$annonceTable) {
            self::$annonceTable = $sm->get('Annoncea\Model\AnnonceTable');
        }
        return self::$annonceTable;
    }
    
    public static function getPhotoTable($sm)
    {
        if (!self::$photoTable) {
            self::$photoTable = $sm->get('Annoncea\Model\PhotoTable');
        }
        return self::$photoTable;
    }
    
    
    public static function getFavorisTable($sm)
    {
        if (!self::$favorisTable) {
            self::$favorisTable = $sm->get('Annoncea\Model\FavorisTable');
        }
        return self::$favorisTable;
    }
    
    
    
    public static function getDepartementTable($sm)
    {
        if (!self::$departementTable) {
            self::$departementTable = $sm->get('Annoncea\Model\DepartementTable');
        }
        return self::$departementTable;
    }
    
    public static function getCategorieTable($sm)
    {
        if (!self::$categorieTable) {
            self::$categorieTable = $sm->get('Annoncea\Model\CategorieTable');
        }
        return self::$categorieTable;
    }
    
    
    public static function getUtilisateurTable($sm)
    {
        if (!self::$utilisateurTable) {
            self::$utilisateurTable = $sm->get('Annoncea\Model\UtilisateurTable');
        }
        return self::$utilisateurTable;
    }
    
        
    public static function getRegionTable($sm)
    {
        if (!self::$regionTable) {
            self::$regionTable = $sm->get('Annoncea\Model\RegionTable');
        }
        return self::$regionTable;
    }
    
    
    public static function getSelecteurCategorie($sm)
    {
        $categorieTable = self::getCategorieTable($sm);
        $categories = $categorieTable->fetchAll();
        $choixCategorie = array();
        foreach ($categories as $categorie) {
            $choixCategorie[$categorie->id_cat] = $categorie->lib_cat;
        } 
        return $choixCategorie;  
    }
    
    public static function getSelecteurDepartement($sm)
    {
         $departementTable = self::getDepartementTable($sm);
         $departements = $departementTable->fetchAll();
         $choixDepartement = array();
         foreach ($departements as $departement) {
               $choixDepartement[$departement->id_dept] = $departement->id_dept . ' - ' . $departement->lib_dept;
         }    
         return $choixDepartement;   
    }
	
	public static function getSelecteurRegion($sm)
    {
         $regionTable = self::getRegionTable($sm);
         $regions = $regionTable->fetchAll();
         $choixregion = array();
         foreach ($regions as $region) {
               $choixregion[$region->id_reg] = $region->lib_reg;
         }    
         return $choixregion;   
    }
    
}