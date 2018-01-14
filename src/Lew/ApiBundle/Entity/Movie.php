<?php

namespace Lew\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Movie
 *
 * @ORM\Table(name="movie")
 * @ORM\Entity(repositoryClass="Lew\ApiBundle\Repository\MovieRepository")
 */
class Movie
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
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="text", nullable=true)
     */
    private $resume;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_sortie", type="date", nullable=true)
     */
    private $dateSortie;

    /**
     * @var int
     *
     * @ORM\Column(name="note", type="integer", nullable=true)
     */
    private $note;

    /**
     * @var int
     *
     * @ORM\Column(name="duree", type="integer", nullable=true)
     */
    private $duree;

    /**
     * @ORM\ManyToMany(targetEntity="Lew\ApiBundle\Entity\Genre", cascade={"persist"})
     */
    private $genres;

    /**
     * @ORM\ManyToMany(targetEntity="Lew\ApiBundle\Entity\Country", cascade={"persist"})
     */
    private $countries;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="Lew\ApiBundle\Entity\Casting", mappedBy="movie", cascade={"persist", "remove"})
     */
    private $castings;

    /**
     * @ORM\Column(name="date_ajout", type="datetime", nullable=true)
     */
    private $dateAjout;

    /**
     * @var boolean
     *
     * @ORM\Column(name="vu", type="boolean")
     */
    private $vu;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->genres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Movie
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
     * Set title
     *
     * @param string $title
     *
     * @return Movie
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set resume
     *
     * @param string $resume
     *
     * @return Movie
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return string
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Set dateSortie
     *
     * @param \DateTime $dateSortie
     *
     * @return Movie
     */
    public function setDateSortie($dateSortie)
    {
        $this->dateSortie = new \DateTime($dateSortie);

        return $this;
    }

    /**
     * Get dateSortie
     *
     * @return \DateTime
     */
    public function getDateSortie()
    {
        return $this->dateSortie;
    }

    /**
     * Set note
     *
     * @param integer $note
     *
     * @return Movie
     */
    public function setNote($note)
    {
        $this->note = ($note * 10);

        return $this;
    }

    /**
     * Get note
     *
     * @return integer
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set duree
     *
     * @param integer $duree
     *
     * @return Movie
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return integer
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Movie
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
     * Add genre
     *
     * @param \Lew\ApiBundle\Entity\Genre $genre
     *
     * @return Movie
     */
    public function addGenre(\Lew\ApiBundle\Entity\Genre $genre)
    {
        $this->genres[] = $genre;

        return $this;
    }

    /**
     * Remove genre
     *
     * @param \Lew\ApiBundle\Entity\Genre $genre
     */
    public function removeGenre(\Lew\ApiBundle\Entity\Genre $genre)
    {
        $this->genres->removeElement($genre);
    }

    /**
     * Get genres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGenres()
    {
        return $this->genres;
    }

    /**
     * Add country
     *
     * @param \Lew\ApiBundle\Entity\Country $country
     *
     * @return Movie
     */
    public function addCountry(\Lew\ApiBundle\Entity\Country $country)
    {
        $this->countries[] = $country;

        return $this;
    }

    /**
     * Remove country
     *
     * @param \Lew\ApiBundle\Entity\Country $country
     */
    public function removeCountry(\Lew\ApiBundle\Entity\Country $country)
    {
        $this->countries->removeElement($country);
    }

    /**
     * Get countries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCountries()
    {
        return $this->countries;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Add casting
     *
     * @param \Lew\ApiBundle\Entity\Casting $casting
     *
     * @return Movie
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

    /**
     * @return mixed
     */
    public function getDateAjout()
    {
        return $this->dateAjout;
    }

    /**
     * @param mixed $dateAjout
     */
    public function setDateAjout()
    {
        $this->dateAjout = new \DateTime();
    }

    /**
     * @return bool
     */
    public function isVu()
    {
        return $this->vu;
    }

    /**
     * @param bool $vu
     */
    public function setVu($vu)
    {
        $this->vu = $vu;
    }
}
