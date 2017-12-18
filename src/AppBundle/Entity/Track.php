<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Track
 *
 * @ORM\Table(
 *     name="track",
 *     indexes={@ORM\Index(name="IDX_D6E3F8A6B7970CF8",
 *     columns={"artist_id"})}
 * )
 * @ORM\Entity
 * @Serializer\ExclusionPolicy("all")
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route(
 *         "get_track",
 *         parameters = {
 *             "guid" = "expr(object.getGuid())"
 *         }
 *    ),
 *    exclusion = @Hateoas\Exclusion({"List"})
 * )
 * @Hateoas\Relation(
 *     "tracks",
 *     href = @Hateoas\Route("get_tracks"),
 *     exclusion = @Hateoas\Exclusion({"Detail"})
 * )
 */
class Track
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     * @Serializer\Expose
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="guid", type="string", length=36, nullable=false)
     * @Serializer\Expose
     */
    private $guid;

    /**
     * @var \Artist
     *
     * @ORM\ManyToOne(targetEntity="Artist")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="artist_id", referencedColumnName="id")
     * })
     * @Serializer\Expose
     * @Serializer\Groups({"Detail"})
     */
    private $artist;



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
     * @return Track
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
     * Set guid
     *
     * @param string $guid
     *
     * @return Track
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid
     *
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set artist
     *
     * @param \AppBundle\Entity\Artist $artist
     *
     * @return Track
     */
    public function setArtist(\AppBundle\Entity\Artist $artist = null)
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Get artist
     *
     * @return \AppBundle\Entity\Artist
     */
    public function getArtist()
    {
        return $this->artist;
    }
}
