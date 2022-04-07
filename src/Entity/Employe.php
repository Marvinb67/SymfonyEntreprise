<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmployeRepository::class)
 */
class Employe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $prenom;

    /**
     * @ORM\Column(type="date")
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="date")
     */
    private $dateEmbauche;

    /**
     * @ORM\ManyToOne(targetEntity=Entreprise::class, inversedBy="employes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $entreprise;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageFileName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(?string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getDateEmbauche(): ?\DateTimeInterface
    {
        return $this->dateEmbauche;
    }

    public function setDateEmbauche(\DateTimeInterface $dateEmbauche): self
    {
        $this->dateEmbauche = $dateEmbauche;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getImageFileName(): ?string
    {
        return $this->imageFileName;
    }

    public function setImageFileName(?string $imageFileName): self
    {
        $this->imageFileName = $imageFileName;

        return $this;
    }

    // Nouvelles méthodes

    //Affiche l'adresse complete d'un employé (Adresse, ville, code postale)

    public function adresseFull()
    {
        return $this->adresse.' '.$this->cp.' '.$this->ville;
    }

    // Affiche la liste des collegues d'un employé

    public function getCollegues()
    {
        return $this->getEntreprise()->getEmployes();
    }

    // Calcul l'age d'une personne a partir de sa date de naissanvce

    public function getAge()
    {
        $ajd = new \DateTime();
        $anniversaire = $this->dateNaissance;

        return $ajd->diff($anniversaire, true)->format('%Y ans');
    }

    // Calcul de l'ancienté d'un employé

    public function getAnciente()
    {
        $ajd = new \DateTime();
        $debut = $this->dateEmbauche;

        return $ajd->diff($debut, true)->format('%y ans %m mois');
    }

    public function __toString()
    {
        return $this->nom.' '.$this->prenom;
    }
}
