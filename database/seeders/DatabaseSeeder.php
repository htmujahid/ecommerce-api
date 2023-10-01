<?php

namespace Database\Seeders;

use App\Filament\Resources\OrderResource;
use App\Models\Address;
use App\Models\Comment;
use App\Models\Brand;
use App\Models\Category as ShopCategory;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Closure;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Helper\ProgressBar;

class DatabaseSeeder extends Seeder
{
    const IMAGE_URL = 'https://source.unsplash.com/random/200x200/?img=1';

    public function run(): void
    {
        // Clear images
        Storage::deleteDirectory('public');

        // Admin
        $this->command->warn(PHP_EOL . 'Creating admin user...');
        $user = $this->withProgressBar(1, fn () => User::factory(1)->create([
            'name' => 'Demo User',
            'email' => 'admin@ecommerce.com',
        ]));
        $this->command->info('Admin user created.');

        // Shop
        $this->command->warn(PHP_EOL . 'Creating shop brands...');
        $brands = $this->withProgressBar(20, fn () => Brand::factory()->count(20)
            ->has(Address::factory()->count(rand(1, 3)))
            ->create());
        $this->command->info('Shop brands created.');

        $this->command->warn(PHP_EOL . 'Creating shop categories...');
        $categories = $this->withProgressBar(20, fn () => ShopCategory::factory(1)
            ->has(
                ShopCategory::factory()->count(3),
                'children'
            )->create());
        $this->command->info('Shop categories created.');

        $this->command->warn(PHP_EOL . 'Creating shop sellers...');
        $sellers = $this->withProgressBar(10, fn () => Seller::factory(1)
            ->has(Address::factory()->count(rand(1, 3)))
            ->create());
        $this->command->info('Shop sellers created.');

        $this->command->warn(PHP_EOL . 'Creating shop customers...');
        $customers = $this->withProgressBar(1000, fn () => Customer::factory(1)
            ->has(Address::factory()->count(rand(1, 3)))
            ->create());
        $this->command->info('Shop customers created.');

        $this->command->warn(PHP_EOL . 'Creating shop products...');
        $products = $this->withProgressBar(50, fn () => Product::factory(1)
            ->sequence(fn ($sequence) => ['brand_id' => $brands->random(1)->first()->id])
            ->sequence(fn ($sequence) => ['seller_id' => $sellers->random(1)->first()->id])
            ->hasAttached($categories->random(rand(3, 6)), ['created_at' => now(), 'updated_at' => now()])
            ->has(
                Comment::factory()->count(rand(10, 20))
                    ->state(fn (array $attributes, Product $product) => ['customer_id' => $customers->random(1)->first()->id]),
            )
            ->create());
        $this->command->info('Shop products created.');

        $this->command->warn(PHP_EOL . 'Creating orders...');
        $orders = $this->withProgressBar(1000, fn () => Order::factory(1)
            ->sequence(fn ($sequence) => ['customer_id' => $customers->random(1)->first()->id])
            ->has(Payment::factory()->count(rand(1, 3)))
            ->has(
                OrderItem::factory()->count(rand(2, 5))
                    ->state(fn (array $attributes, Order $order) => ['product_id' => $products->random(1)->first()->id]),
                'items'
            )
            ->create());

        foreach ($orders->random(rand(5, 8)) as $order) {
            Notification::make()
                ->title('New order')
                ->icon('heroicon-o-shopping-bag')
                ->body("{$order->customer->name} ordered {$order->items->count()} products.")
                ->actions([
                    Action::make('View')
                        ->url(OrderResource::getUrl('edit', ['record' => $order])),
                ])
                ->sendToDatabase($user);
        }
        $this->command->info('Shop orders created.');

    }

    protected function withProgressBar(int $amount, Closure $createCollectionOfOne): Collection
    {
        $progressBar = new ProgressBar($this->command->getOutput(), $amount);

        $progressBar->start();

        $items = new Collection();

        foreach (range(1, $amount) as $i) {
            $items = $items->merge(
                $createCollectionOfOne()
            );
            $progressBar->advance();
        }

        $progressBar->finish();

        $this->command->getOutput()->writeln('');

        return $items;
    }
}
