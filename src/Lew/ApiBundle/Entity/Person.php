<?php

namespace Lew\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Person
 *
 * @ORM\Table(name="person")
 * @ORM\Entity(repositoryClass="Lew\ApiBundle\Repository\PersonRepository")
 */
class Person
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="date", nullable=true)
     */
    private $birthDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="death_date", type="date", nullable=true)
     */
    private $deathDate;

    /**
     * @var int
     *
     * @ORM\Column(name="sexe", type="integer", nullable=true)
     */
    private $sexe;

    /**
     * @var string
     *
     * @ORM\Column(name="biographie", type="text", nullable=true)
     */
    private $biographie;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="birth_place", type="string", nullable=true)
     */
    private $birthPlace;

    /**
     * @ORM\OneToMany(targetEntity="Lew\ApiBundle\Entity\Casting", mappedBy="person")
     */
    private $castings;


    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Person
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Person
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return Person
     */
    public function setBirthDate($birthDate = null)
    {
        if ($birthDate != null){
            $this->birthDate = new \DateTime($birthDate);
        }else{
            $this->birthDate = null;
        }
        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set deathDate
     *
     * @param \DateTime $deathDate
     *
     * @return Person
     */
    public function setDeathDate($deathDate = null)
    {
        if($deathDate != null){
            $this->deathDate = new \DateTime($deathDate);
        }else{
            $this->deathDate = null;
        }
        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getDeathDate()
    {
        return $this->deathDate;
    }

    /**
     * Set sexe
     *
     * @param integer $sexe
     *
     * @return Person
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;
        return $this;
    }

    /**
     * Get sexe
     *
     * @return integer
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Person
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set birthPlace
     *
     * @param string $birthPlace
     *
     * @return Person
     */
    public function setBirthPlace($birthPlace)
    {
        $this->birthPlace = $birthPlace;
        return $this;
    }

    /**
     * Get nationalite
     *
     * @return string
     */
    public function getBirthPlace()
    {
        return $this->birthPlace;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->castings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add casting
     *
     * @param \Lew\ApiBundle\Entity\Casting $casting
     *
     * @return Person
     */
    public function addCasting(\Lew\ApiBundle\Entity\Casting $casting)
    {
        $this->castings[] = $casting;
        return $this;
    }

    /**
     * Remove casting
     *
     * @param \Lew\ApiBundle\Entity\Casting $casting
     */
    public function removeCasting(\Lew\ApiBundle\Entity\Casting $casting)
    {
        $this->castings->removeElement($casting);
    }

    /**
     * Get castings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCastings()
    {
        return $this->castings;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getBiographie()
    {
        return $this->biographie;
    }

    /**
     * Get biographie
     *
     * @param string $biographie
     */
    public function setBiographie($biographie)
    {
        $this->biographie = $biographie;
    }

    public function getAge()
    {
        return $this->getBirthDate()->diff(new \DateTime())->format('%Y');
    }
}
