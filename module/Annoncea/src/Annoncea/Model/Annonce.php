<?php
namespace Annoncea\Model;
/*
 * donne la structure d'une ligne de la table annonce 
 */


class Annonce
{
	
	public $id_annonce;
	public $titre;
	public $descr;	
	public $type_annonce;	
	public $prix;
	public $etat; 
	public $date_crea; 
	public $date_modif;
	public $visible; 
	public $id_cat; 
	public $id_dept; 
	public $mail_auteur; 
    public $id_reg;

 
    public function exchangeArray($data)
    {
        $this->id_annonce  = (!empty($data['id_annonce'])) ? $data['id_annonce'] : null; /*si la clé id correspond à une valeur on prend cette valeurr là)*/
        $this->titre = (!empty($data['titre'])) ? $data['titre'] : null;
        $this->descr = (!empty($data['descr'])) ? $data['descr'] : null;
        $this->type_annonce = (!empty($data['type_annonce'])) ? $data['type_annonce'] : null;
        $this->prix = (!empty($data['prix'])) ? $data['prix'] : null;
        $this->etat = (!empty($data['etat'])) ? $data['etat'] : null;
        $this->date_crea = (!empty($data['date_crea'])) ? $data['date_crea'] : null;
        $this->date_modif = (!empty($data['date_modif'])) ? $data['date_modif'] : null;
        $this->visible = (!empty($data['visible'])) ? $data['visible'] : null;
        $this->id_cat = (!empty($data['id_cat'])) ? $data['id_cat'] : null;
        $this->id_dept = (!empty($data['id_dept'])) ? $data['id_dept'] : null;
        $this->mail_auteur = (!empty($data['mail_auteur'])) ? $data['mail_auteur'] : null;
        $this->id_reg = (!empty($data['id_reg'])) ? $data['id_reg'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    
        
    public function pertinent($champRecherche, $titreUniquement) {

            $pertinenceTotale = 100;
            
            $coeffLevDesc = 1; 
            $coeffSimDesc = 0;
            $coeffLevTitre = 1; 
            $coeffSimTitre = 1;

            if($champRecherche != null) {
                $pertinenceTotale = 0;
                $pertinenceDesc = 0;
                $champRecherche = explode(" ", $champRecherche);
                
                foreach ($champRecherche as $recherche) {
                //recherche dans le titre
                $diffTitre = levenshtein($this->titre, $recherche);
                $levTitre = $diffTitre - abs(strlen($this->titre) - strlen($recherche));
                $levTitre = (1 - ($levTitre / (float)min(strlen($this->titre), strlen($recherche))))*100;              
                similar_text($this->titre, $recherche, $simTitre);
                
                echo 'diffTitre : ' . $diffTitre;
                echo 'levTitre : ' . $levTitre;
                
                
                $pertinenceTitre = ($simTitre*$coeffSimTitre + $levTitre*$coeffLevTitre) / ($coeffSimTitre + $coeffLevTitre);
                echo 'pertinence titre : ' . $pertinenceTitre;
               
                if(!$titreUniquement){
                    //recherche dans la description 
                    $diffDesc = levenshtein($this->descr, $recherche);
                    $levDesc = $diffDesc - abs(strlen($this->descr) - strlen($recherche));
                    $levDesc  = (1 - ($levDesc / (float)min(strlen($this->descr), strlen($recherche))))*100;              
                    similar_text($this->descr, $recherche, $simDesc);
                    
                    echo 'diffDesc : ' . $diffDesc; 
                    echo 'levDesc : ' . $levDesc; 
          
                    $pertinenceDesc = ($simDesc*$coeffSimDesc + $levDesc*$coeffLevDesc) / ($coeffLevDesc + $coeffSimDesc);
                    echo 'pertinence desc : ' . $pertinenceDesc;
                }
                
                $pertinenceTotale += max($pertinenceDesc, $pertinenceTitre);
                }
                
                $pertinenceTotale /= count($champRecherche);
                echo 'pertinence totale : ' . $pertinenceTotale;
            }
        
        return ($pertinenceTotale > 70); 
    }

    //à quoi ça sert déjà ??? 
    public function filtrageStrict($prixmin, $prixmax, $id_cat, $id_dept, $type_annonce, $id_reg, $etat) {
        if($id_cat != null && $id_cat != $this->id_cat)
            return false; 
        
        if($id_dept != null && $id_dept != $this->id_dept)
           return false;

        if($type_annonce != null && $type_annonce != $this->type_annonce)
            return false;
            
        if($prixmin != null && $prixmin > $this->prix)
            return false;   
        
        if($prixmax != null && $prixmax < $this->prix)
            return false; 
            
         if($id_reg != null && $id_reg != $this->id_reg)
            return false;  
         
         if($etat != null && $etat > $this->etat)
            return false;
         
        return true;
        
      }
    
}