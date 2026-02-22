<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MailAccount>
 */
class MailAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $emails = [
            'gmail.com',
            'outlook.com',
            'icloud.com',
            'yahoo.com',
        ];

        return [
            'name' => $this->faker->word().' Account',
            'email' => $this->faker->unique()->email(),
            'server' => $this->faker->randomElement([
                'imap.gmail.com',
                'imap-mail.outlook.com',
                'imap.mail.yahoo.com',
                'imap.mail.icloud.com',
            ]),
            'password' => encrypt('password123'),
            'imap_port' => $this->faker->randomElement([993, 143]),
            'imap_security' => $this->faker->randomElement(['ssl', 'tls']),
            'smtp_port' => $this->faker->randomElement([587, 465]),
            'smtp_security' => $this->faker->randomElement(['ssl', 'tls']),
            'is_active' => true,
            'last_synced_at' => $this->faker->dateTimeThisMonth(),
        ];
    }
}
