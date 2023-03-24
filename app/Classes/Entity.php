<?php

namespace Fab\Classes;


class Entity implements \JsonSerializable{

    protected ?int $id = null;


    /**
     * Get the value of id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /** Setter magique. 
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, mixed $value)
    {
        // Si le setter existe on l'appel avec la valeur
        if (method_exists($this, 'set' . ucfirst($name)))
            $this->{'set' . ucfirst($name)}($value);
        else
            throw new \Exception('La méthode set' . ucfirst($name) . '() n\'existe pas !');
    }


    /**
     * Getter magique
     *
     * @param string $name
     * 
     * @return mixed
     * 
     */
    public function __get(string $name)
    {
        //return $this->$name;  ! interdit on respect le developpeur.. on passe par le getter :!!
        if (method_exists($this, 'get' . ucfirst($name)))
            return $this->{'get' . ucfirst($name)}();
        else
            throw new \Exception('La méthode get' . ucfirst($name) . '() n\'existe pas !');
    }


    /** Permet de renvoyer un tableau pour structurer une requête d'hydratation  des DATA
     * ['colonne'=>'value','colonne'=>'value']
     * @param void
     * @return array
     */
    public function getPropertyHydrateData(): array
    {
        //utilisation du Json pour convertir en tableau associatif
        $properties = json_decode(json_encode($this->jsonSerialize()),true);
        // on enlève le type ajouter dans le json, ici on en a pas besoin
        unset($properties['type']);
        return $properties;
    }

    /**
     * Permet de sérialisé la form en JSON !
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        $reflect = new \ReflectionClass($this);
        $props   = $reflect->getProperties();
        $class   = $reflect->getName();

        $obj = new \stdClass();

        // Ajout du type pour reconstruire éventuellement l'objet !
        $obj->type = $class;
      
        foreach ($props as $prop) {
            $obj->{$prop->name} = $this->{$prop->name};
        }

        return $obj;
    }
}