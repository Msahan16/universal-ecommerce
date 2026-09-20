<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Kasun Perera',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );

        // 2. Site Settings (Universal CMS)
        $settings = [
            'site_name' => 'Universal Store',
            'site_tagline' => 'The Next-Gen Multi-Industry Commerce Engine',
            'logo' => '',
            'hero_badge' => '⚡ Premium Quality & Fast Delivery Islandwide',
            'hero_title' => 'Engineered for Performance. Built for Reliability.',
            'hero_subtitle' => 'Explore industry-leading solutions, high-grade materials, precision spare parts, and custom fabrication components with instant doorstep delivery.',
            'hero_cta_text' => 'Shop Full Catalog',
            'hero_cta_link' => '/shop',
            'hero_image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=800&auto=format&fit=crop&q=80',
            'promo_banner_title' => 'Limited Time Mega Deal!',
            'promo_banner_subtitle' => 'Get up to 25% off on high-grade commercial profiles and hardware packages.',
            'promo_banner_code' => 'WELCOME10',
            'contact_phone' => '+94 11 234 5678',
            'contact_email' => 'support@universal-store.lk',
            'contact_address' => '45 Tech Avenue, Industrial Zone, Colombo 03, Sri Lanka',
            'currency_symbol' => 'Rs. ',
            'free_shipping_threshold' => '15000',
            'flat_shipping_rate' => '450',
            'section_hero_enabled' => '1',
            'section_categories_enabled' => '1',
            'section_featured_enabled' => '1',
            'section_promo_enabled' => '1',
            'section_features_enabled' => '1',
            'section_testimonials_enabled' => '1',
        ];

        foreach ($settings as $key => $val) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $val]);
        }

        // 3. Categories
        $categoriesData = [
            [
                'name' => 'Aluminium Profiles & Systems',
                'slug' => 'aluminium-profiles-and-systems',
                'description' => 'Heavy-duty architectural profiles, sliding sections, curtain walls and casement systems.',
                'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=600&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Doors & Windows',
                'slug' => 'doors-and-windows',
                'description' => 'Custom double-glazed sliding windows, bi-fold doors, and acoustic tempered glass systems.',
                'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=600&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Auto Spare Parts',
                'slug' => 'auto-spare-parts',
                'description' => 'OEM precision components, braking systems, suspensions, and engine replacement units.',
                'image' => 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?w=600&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Hardware & Tools',
                'slug' => 'hardware-and-tools',
                'description' => 'Industrial power tools, fasteners, multi-point locks, rollers, and accessories.',
                'image' => 'https://images.unsplash.com/photo-1581244277943-fe4a9c777189?w=600&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'sort_order' => 4,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::create($cat);
        }

        // 4. Brands
        $brandsData = [
            ['name' => 'Alumex', 'slug' => 'alumex', 'description' => 'Leading manufacturer of architectural aluminium.'],
            ['name' => 'Swisstek', 'slug' => 'swisstek', 'description' => 'Premium Swiss-standard aluminium extrusion.'],
            ['name' => 'Bosch', 'slug' => 'bosch', 'description' => 'German engineering tools and automotive technology.'],
            ['name' => 'Brembo', 'slug' => 'brembo', 'description' => 'High-performance brake systems and components.'],
            ['name' => 'Makita', 'slug' => 'makita', 'description' => 'Industrial power tools and fabrication gear.'],
            ['name' => 'Denso', 'slug' => 'denso', 'description' => 'Global automotive components & ignition systems.'],
        ];

        $brands = [];
        foreach ($brandsData as $b) {
            $brands[$b['slug']] = Brand::create($b);
        }

        // 5. Dynamic Attributes
        $attrColor = Attribute::create(['name' => 'Color / Finish', 'slug' => 'color-finish', 'type' => 'color']);
        $attrThickness = Attribute::create(['name' => 'Profile Thickness', 'slug' => 'profile-thickness', 'type' => 'select']);
        $attrGlass = Attribute::create(['name' => 'Glass Type', 'slug' => 'glass-type', 'type' => 'select']);
        $attrVehicle = Attribute::create(['name' => 'Vehicle Model', 'slug' => 'vehicle-model', 'type' => 'text']);
        $attrWarranty = Attribute::create(['name' => 'Warranty', 'slug' => 'warranty', 'type' => 'text']);

        // Attribute Values
        $valMatteBlack = AttributeValue::create(['attribute_id' => $attrColor->id, 'value' => 'Matte Black (Powder Coated)', 'color_code' => '#1a1a1a']);
        $valWoodFinish = AttributeValue::create(['attribute_id' => $attrColor->id, 'value' => 'Natural Oak Wood Grain', 'color_code' => '#8b5a2b']);
        $valSilverAnodized = AttributeValue::create(['attribute_id' => $attrColor->id, 'value' => 'Silver Anodized', 'color_code' => '#c0c0c0']);

        $val12mm = AttributeValue::create(['attribute_id' => $attrThickness->id, 'value' => '1.2 mm Standard']);
        $val15mm = AttributeValue::create(['attribute_id' => $attrThickness->id, 'value' => '1.5 mm Heavy Duty']);
        $val20mm = AttributeValue::create(['attribute_id' => $attrThickness->id, 'value' => '2.0 mm Commercial']);

        $valClear8mm = AttributeValue::create(['attribute_id' => $attrGlass->id, 'value' => '8mm Clear Tempered Glass']);
        $valTintedDouble = AttributeValue::create(['attribute_id' => $attrGlass->id, 'value' => 'Double Glazed Tinted Acoustic']);

        // 6. Products
        $productsData = [
            [
                'name' => '70mm Heavy Duty Sliding Window System',
                'slug' => '70mm-heavy-duty-sliding-window-system',
                'category_id' => $categories['doors-and-windows']->id,
                'brand_id' => $brands['alumex']->id,
                'sku' => 'WIN-SLD-7001',
                'short_description' => 'Modern 2-track or 3-track sliding window system engineered for maximum acoustic insulation and wind resistance.',
                'description' => 'Engineered from premium architectural alloy 6063-T6. Features smooth nylon roller tracks, water drainage channels, EPDM perimeter weather-stripping, and multi-point secure locking mechanisms.',
                'price' => 38500.00,
                'compare_price' => 45000.00,
                'stock' => 24,
                'thumbnail' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => true,
                'has_variants' => true,
                'attrs' => [
                    ['attr' => $attrThickness, 'val' => $val15mm],
                    ['attr' => $attrGlass, 'val' => $valClear8mm],
                    ['attr' => $attrWarranty, 'custom' => '10 Years Structural Warranty'],
                ],
                'variants' => [
                    ['name' => 'Matte Black / 1.5mm', 'sku' => 'WIN-7001-BLK', 'price' => 38500, 'stock' => 12, 'attrs' => ['Color' => 'Matte Black', 'Thickness' => '1.5 mm']],
                    ['name' => 'Silver Anodized / 1.5mm', 'sku' => 'WIN-7001-SLV', 'price' => 36000, 'stock' => 8, 'attrs' => ['Color' => 'Silver Anodized', 'Thickness' => '1.5 mm']],
                    ['name' => 'Wood Grain / 1.5mm', 'sku' => 'WIN-7001-WDG', 'price' => 42000, 'stock' => 4, 'attrs' => ['Color' => 'Wood Grain', 'Thickness' => '1.5 mm']],
                ],
            ],
            [
                'name' => 'Architectural Curtain Wall Mullion Profile (6m)',
                'slug' => 'architectural-curtain-wall-mullion-profile-6m',
                'category_id' => $categories['aluminium-profiles-and-systems']->id,
                'brand_id' => $brands['swisstek']->id,
                'sku' => 'PRF-CW-600',
                'short_description' => 'Structural load-bearing extruded aluminium mullion for high-rise commercial facade systems.',
                'description' => 'Precision engineered for structural stability, thermal expansion resistance, and seamless architectural glazing integration. High tensile strength 6063 T6 temper.',
                'price' => 19800.00,
                'compare_price' => 22500.00,
                'stock' => 45,
                'thumbnail' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => true,
                'has_variants' => false,
                'attrs' => [
                    ['attr' => $attrThickness, 'val' => $val20mm],
                    ['attr' => $attrWarranty, 'custom' => '15 Years Anodizing Warranty'],
                ],
            ],
            [
                'name' => 'Ceramic Performance Disc Brake Pad Set',
                'slug' => 'ceramic-performance-disc-brake-pad-set',
                'category_id' => $categories['auto-spare-parts']->id,
                'brand_id' => $brands['brembo']->id,
                'sku' => 'BRK-CER-902',
                'short_description' => 'Ultra-quiet, low-dust premium ceramic brake pads designed for maximum stopping power and heat dissipation.',
                'description' => 'Manufactured with OE multi-layer shim technology for noise dampening and scorched surface friction pads for rapid bedding-in. Suitable for modern SUVs and Japanese sedans.',
                'price' => 14500.00,
                'compare_price' => 17000.00,
                'stock' => 32,
                'thumbnail' => 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => true,
                'has_variants' => false,
                'attrs' => [
                    ['attr' => $attrVehicle, 'custom' => 'Toyota Land Cruiser Prado / Hilux Revo / Fortuner'],
                    ['attr' => $attrWarranty, 'custom' => '30,000 km Manufacturer Warranty'],
                ],
            ],
            [
                'name' => 'Makita 18V Cordless Brushless Rotary Hammer Drill',
                'slug' => 'makita-18v-cordless-brushless-rotary-hammer-drill',
                'category_id' => $categories['hardware-and-tools']->id,
                'brand_id' => $brands['makita']->id,
                'sku' => 'TLS-MKT-DHR242',
                'short_description' => 'Heavy duty 24mm SDS-Plus 3-mode rotary hammer for concrete anchoring, chiseling, and masonry fabrication.',
                'description' => 'High-efficiency brushless motor delivers 2.0 Joules of impact energy. Equipped with anti-vibration technology (AVT) and torque limiting clutch to protect motor gearing.',
                'price' => 64000.00,
                'compare_price' => 72000.00,
                'stock' => 10,
                'thumbnail' => 'https://images.unsplash.com/photo-1581244277943-fe4a9c777189?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => true,
                'has_variants' => false,
                'attrs' => [
                    ['attr' => $attrWarranty, 'custom' => '2 Years Official Service Warranty'],
                ],
            ],
            [
                'name' => 'Bi-Fold Tempered Glass Patio Door System',
                'slug' => 'bi-fold-tempered-glass-patio-door-system',
                'category_id' => $categories['doors-and-windows']->id,
                'brand_id' => $brands['alumex']->id,
                'sku' => 'DOR-BF-400',
                'short_description' => 'Floor-to-ceiling panoramic folding door system with effortless roller operation and flush threshold.',
                'description' => 'Transform interior-exterior living spaces with panoramic bi-folding panels. Features concealed multipoint lock rods, anti-lift security blocks, and thermal break extrusion.',
                'price' => 145000.00,
                'compare_price' => 165000.00,
                'stock' => 6,
                'thumbnail' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => true,
                'has_variants' => false,
                'attrs' => [
                    ['attr' => $attrThickness, 'val' => $val20mm],
                    ['attr' => $attrGlass, 'val' => $valTintedDouble],
                    ['attr' => $attrWarranty, 'custom' => '10 Years Structural Warranty'],
                ],
            ],
            [
                'name' => 'Denso Iridium Power Spark Plug Set (Pack of 4)',
                'slug' => 'denso-iridium-power-spark-plug-set-pack-of-4',
                'category_id' => $categories['auto-spare-parts']->id,
                'brand_id' => $brands['denso']->id,
                'sku' => 'AUT-DNS-IK20',
                'short_description' => '0.4mm laser welded ultra-fine iridium center electrode for maximum combustion and fuel efficiency.',
                'description' => 'Improves throttle response, acceleration, and cold engine starts while maintaining stable idling. Platinum ground electrode offers exceptional thermal conductivity.',
                'price' => 12500.00,
                'compare_price' => 14000.00,
                'stock' => 50,
                'thumbnail' => 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => false,
                'has_variants' => false,
                'attrs' => [
                    ['attr' => $attrVehicle, 'custom' => 'Honda Civic / Vezel, Toyota Premio / Allion / Aqua, Nissan X-Trail'],
                    ['attr' => $attrWarranty, 'custom' => '50,000 km Service Life Guarantee'],
                ],
            ]
        ];

        foreach ($productsData as $pData) {
            $attrs = $pData['attrs'] ?? [];
            $variants = $pData['variants'] ?? [];
            unset($pData['attrs'], $pData['variants']);

            $product = Product::create($pData);

            // Add additional gallery images
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $product->thumbnail,
                'sort_order' => 1,
            ]);

            // Add attribute values
            foreach ($attrs as $a) {
                ProductAttributeValue::create([
                    'product_id' => $product->id,
                    'attribute_id' => $a['attr']->id,
                    'attribute_value_id' => isset($a['val']) ? $a['val']->id : null,
                    'custom_value' => $a['custom'] ?? null,
                ]);
            }

            // Add variants
            foreach ($variants as $v) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $v['name'],
                    'sku' => $v['sku'],
                    'price' => $v['price'],
                    'stock' => $v['stock'],
                    'attributes_json' => $v['attrs'],
                    'is_active' => true,
                ]);
            }
        }

        // 7. Coupons
        Coupon::create([
            'code' => 'WELCOME10',
            'type' => 'percentage',
            'value' => 10,
            'min_spend' => 5000,
            'max_discount' => 5000,
            'usage_limit' => 500,
            'used_count' => 14,
            'expires_at' => now()->addMonths(6),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'SAVE1000',
            'type' => 'fixed',
            'value' => 1000,
            'min_spend' => 15000,
            'usage_limit' => 100,
            'used_count' => 8,
            'expires_at' => now()->addMonths(3),
            'is_active' => true,
        ]);

        // 8. Sample Orders
        $order1 = Order::create([
            'order_number' => 'ORD-2026-000101',
            'user_id' => $customer->id,
            'customer_name' => 'Kasun Perera',
            'customer_email' => 'customer@example.com',
            'customer_phone' => '077 123 4567',
            'shipping_address' => 'No 12, Galle Road, Bambalapitiya',
            'city' => 'Colombo',
            'state' => 'Western Province',
            'postal_code' => '00400',
            'payment_method' => 'card',
            'payment_status' => 'paid',
            'status' => 'delivered',
            'subtotal' => 38500.00,
            'discount' => 3850.00,
            'coupon_code' => 'WELCOME10',
            'shipping_fee' => 0.00,
            'total' => 34650.00,
            'notes' => 'Please call prior to delivery.',
        ]);

        $firstProduct = Product::first();
        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $firstProduct->id,
            'product_name' => $firstProduct->name,
            'variant_name' => 'Matte Black / 1.5mm',
            'sku' => 'WIN-7001-BLK',
            'price' => 38500.00,
            'quantity' => 1,
            'total' => 38500.00,
            'attributes_snapshot' => ['Color' => 'Matte Black', 'Thickness' => '1.5 mm'],
        ]);

        $order2 = Order::create([
            'order_number' => 'ORD-2026-000102',
            'user_id' => null, // Guest
            'customer_name' => 'Dilshan Silva',
            'customer_email' => 'dilshan.silva@gmail.com',
            'customer_phone' => '071 987 6543',
            'shipping_address' => '45 Main Street, Kadawatha',
            'city' => 'Gampaha',
            'state' => 'Western Province',
            'postal_code' => '11850',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'status' => 'processing',
            'subtotal' => 29000.00,
            'discount' => 0.00,
            'shipping_fee' => 450.00,
            'total' => 29450.00,
            'notes' => 'Deliver on weekday mornings if possible.',
        ]);

        $thirdProduct = Product::where('sku', 'BRK-CER-902')->first();
        if ($thirdProduct) {
            OrderItem::create([
                'order_id' => $order2->id,
                'product_id' => $thirdProduct->id,
                'product_name' => $thirdProduct->name,
                'sku' => $thirdProduct->sku,
                'price' => 14500.00,
                'quantity' => 2,
                'total' => 29000.00,
            ]);
        }
    }
}
