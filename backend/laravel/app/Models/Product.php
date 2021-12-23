<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Interfaces\Products\ProductEntity;
use Exception;

class Product extends Model implements ProductEntity
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable =  ['name', 'description', 'slug', 'price'];
    protected $hidden = ['creator'];

    //------[ RELATIONSHIPS ]------\\
    public function user()
    {
        return $this->belongsTo(User::class, 'creator');
    }

    //------[ DB FUNCTIONS ]------\\
    public function saveProduct(): void
    {
        if (!$this->save()) {
            throw new Exception("Couldn't save Product");
        }
    }

    //------[SETTERS AND GETTERS]------\\

    //ID
    public function getId(): int
    {
        return $this->getKey();
    }

    //STATUS
    public function setStatus(string $status)
    {
        $this->attributes['status'] = $status;
    }

    //CREATOR
    public function getCreator(): ?string
    {
        return $this->attributes['creator'] ?? null;
    }
    public function setCreator(string $creator)
    {
        $this->attributes['creator'] = $creator;
    }

    //NAME
    public function getName(): ?string
    {
        return $this->attributes['name'] ?? null;
    }
    public function setName(string $name)
    {
        $this->attributes['name'] = $name;
    }

    //IMAGE
    public function getImage(): ?string
    {
        return $this->attributes['image'] ?? null;
    }
    public function setImage(string $image)
    {
        $this->attributes['image'] = $image;
    }

    //SLUG
    public function getSlug(): ?string
    {
        return $this->attributes['slug'] ?? null;
    }
    public function setSlug($slug)
    {
        $this->attributes['slug'] = $slug;
    }
}
