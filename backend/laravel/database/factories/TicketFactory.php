<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Ticket;


class TicketFactory extends Factory
{

    protected $model = Ticket::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = ['Report Product', 'Create Product'];
        $status = ['Pending', 'Accepted', 'Cancelled', 'Complete'];
        $rand_type = $type[array_rand($type)];
        $rand_prod_id = random_int(1, 30);
        $rand_text = $this->faker->text;
        $arr = [
            'title' => $this->faker->name(),
            'type' => $rand_type,
            'creator' => null,
            'status' => $status[array_rand($status)],
            'created_at' => time() - (3600 * random_int(0, 168))
        ];

        switch ($rand_type) {
            case 'Report Product':
                $arr['content'] = "{\"productId\":$rand_prod_id, \"message\":\"$rand_text\"}";
                break;
            case 'Create Product':
                $arr['content'] = "{\"message\":\"$rand_text\"}";
                break;
        }

        return $arr;
    }

}

