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

        // 2. Site Settings (Motorbike Spare Parts & Accessories CMS)
        $settings = [
            'site_name' => 'MOTOX SPARES',
            'site_tagline' => 'Genuine Motorbike Parts & Scooter Accessories',
            'logo' => '/images/logo.svg',
            'hero_badge' => '🏍️ 100% Genuine Bike & Scooter Parts • Islandwide Fast Delivery',
            'hero_title' => 'Ride With Confidence. Genuine Moto Spares & Performance Accessories.',
            'hero_subtitle' => 'Sri Lanka\'s premier catalog for Honda Dio, Suzuki Burgman, Yamaha, TVS & Bajaj genuine spare parts, OEM mudguards, LED headlights, drive belts, brake kits & riding accessories.',
            'hero_cta_text' => 'Shop Moto Catalog',
            'hero_cta_link' => '/shop',
            'hero_image' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=900&auto=format&fit=crop&q=80',
            'promo_banner_title' => 'Mega Moto Sale! Up to 20% OFF on Scooter Body Parts & Lighting',
            'promo_banner_subtitle' => 'Upgrade your Honda Dio, Suzuki Burgman, or TVS Ntorq today. Use promo code at checkout.',
            'promo_banner_code' => 'MOTO10',
            'contact_phone' => '+94 11 289 4567 / 077 555 4321',
            'contact_email' => 'support@motox-spares.lk',
            'contact_address' => 'No. 142, High Level Road, Colombo 06 / Pannipitiya, Sri Lanka',
            'currency_symbol' => 'Rs. ',
            'free_shipping_threshold' => '10000',
            'flat_shipping_rate' => '450',
            'section_hero_enabled' => '1',
            'section_categories_enabled' => '1',
            'section_featured_enabled' => '1',
            'section_promo_enabled' => '1',
            'section_features_enabled' => '1',
            'section_testimonials_enabled' => '1',
            'section_quotation_enabled' => '1',
        ];

        foreach ($settings as $key => $val) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $val]);
        }

        // 3. Categories (Motorcycle & Scooter Specific)
        $categoriesData = [
            [
                'name' => 'Body Parts & Mudguards',
                'slug' => 'body-parts-and-mudguards',
                'description' => 'OEM replacement front mudguards, side cowlings, visors, inner shields and full body fairings for scooters & bikes.',
                'image' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=700&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Lighting & Electricals',
                'slug' => 'lighting-and-electricals',
                'description' => 'Full LED headlight units, projector lamps, tail lights, digital meters, CDI units and ignition switches.',
                'image' => 'https://images.unsplash.com/photo-1508974239320-0a029497e820?w=700&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Engine & Transmission',
                'slug' => 'engine-and-transmission',
                'description' => 'CVT drive belts, variator roller weights, clutch assemblies, carburetors, pistons and gasket sets.',
                'image' => 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?w=700&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Brakes & Suspension',
                'slug' => 'brakes-and-suspension',
                'description' => 'Front disc brake calipers, ceramic brake pads, master cylinder pumps, rear shock absorbers and fork seals.',
                'image' => 'https://images.unsplash.com/photo-1600705722908-bab1e61c0b4d?w=700&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Accessories & Protection',
                'slug' => 'accessories-and-protection',
                'description' => 'Heavy-duty crash guards, slider bungs, touring windshields, anti-slip 3D seat covers and phone holders.',
                'image' => 'https://images.unsplash.com/photo-1558980664-769d59546b3d?w=700&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Maintenance & Lubricants',
                'slug' => 'maintenance-and-lubricants',
                'description' => 'Premium 4T synthetic engine oils, NGK Iridium spark plugs, chain lube sprays and high-flow air filters.',
                'image' => 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=700&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'sort_order' => 6,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::create($cat);
        }

        // 4. Brands (Motorcycle Manufacturers & Aftermarket Giants)
        $brandsData = [
            ['name' => 'Honda Genuine Parts', 'slug' => 'honda', 'description' => 'Official genuine spare parts for Honda Dio, Activa, CB Hornet, Navi and Unicorn.'],
            ['name' => 'Suzuki Genuine Parts', 'slug' => 'suzuki', 'description' => 'OEM original components for Suzuki Burgman Street 125, Access 125 and Gixxer 150/250.'],
            ['name' => 'Yamaha Genuine Parts', 'slug' => 'yamaha', 'description' => 'Precision engineering spares for Yamaha FZ-S, MT-15, RayZR, Aerox and R15.'],
            ['name' => 'TVS Genuine Parts', 'slug' => 'tvs', 'description' => 'Factory certified replacement components for TVS Ntorq 125, Apache RTR and Jupiter.'],
            ['name' => 'Bajaj Genuine Parts', 'slug' => 'bajaj', 'description' => 'Authentic OEM spares for Bajaj Pulsar 150/180/220/NS200, Avenger and Discover.'],
            ['name' => 'Brembo', 'slug' => 'brembo', 'description' => 'World standard high-performance motorcycle disc braking systems and master pumps.'],
            ['name' => 'NGK Spark Plugs', 'slug' => 'ngk', 'description' => 'Japanese engineered high-ignition spark plugs for scooters and performance motorbikes.'],
            ['name' => 'Motul', 'slug' => 'motul', 'description' => 'World-renowned 100% synthetic 4T engine oils and performance maintenance fluids.'],
            ['name' => 'Bando OEM', 'slug' => 'bando', 'description' => 'Leading Japanese manufacturer of OEM CVT scooter drive belts and transmission parts.'],
            ['name' => 'Endurance Tech', 'slug' => 'endurance', 'description' => 'Top OEM supplier of motorbike shock absorbers, crash guards and brake calipers.'],
        ];

        $brands = [];
        foreach ($brandsData as $b) {
            $brands[$b['slug']] = Brand::create($b);
        }

        // 5. Dynamic Attributes
        $attrVehicle = Attribute::create(['name' => 'Compatible Vehicle Model', 'slug' => 'compatible-vehicle-model', 'type' => 'text']);
        $attrColor = Attribute::create(['name' => 'Color / Finish', 'slug' => 'color-finish', 'type' => 'color']);
        $attrMaterial = Attribute::create(['name' => 'Material / Build', 'slug' => 'material-build', 'type' => 'select']);
        $attrPosition = Attribute::create(['name' => 'Placement Position', 'slug' => 'placement-position', 'type' => 'select']);
        $attrWarranty = Attribute::create(['name' => 'Warranty Period', 'slug' => 'warranty-period', 'type' => 'text']);

        // Attribute Values
        $valMatteGrey = AttributeValue::create(['attribute_id' => $attrColor->id, 'value' => 'Matte Axis Grey', 'color_code' => '#4b5563']);
        $valSportsYellow = AttributeValue::create(['attribute_id' => $attrColor->id, 'value' => 'Pearl Sports Yellow', 'color_code' => '#eab308']);
        $valMatteBlack = AttributeValue::create(['attribute_id' => $attrColor->id, 'value' => 'Matte Black', 'color_code' => '#18181b']);
        $valCandyRed = AttributeValue::create(['attribute_id' => $attrColor->id, 'value' => 'Candy Blaze Red', 'color_code' => '#dc2626']);
        $valSmokeTint = AttributeValue::create(['attribute_id' => $attrColor->id, 'value' => 'Dark Smoke Tint', 'color_code' => '#334155']);

        $valABS = AttributeValue::create(['attribute_id' => $attrMaterial->id, 'value' => 'High-Impact Virgin ABS Plastic']);
        $valAlloy = AttributeValue::create(['attribute_id' => $attrMaterial->id, 'value' => '6061-T6 CNC Billet Aluminium']);
        $valPolycarb = AttributeValue::create(['attribute_id' => $attrMaterial->id, 'value' => 'Shatterproof Polycarbonate']);

        $valFront = AttributeValue::create(['attribute_id' => $attrPosition->id, 'value' => 'Front Position']);
        $valRear = AttributeValue::create(['attribute_id' => $attrPosition->id, 'value' => 'Rear Position']);
        $valFullSet = AttributeValue::create(['attribute_id' => $attrPosition->id, 'value' => 'Complete Set (Left + Right)']);

        // 6. Comprehensive Motorcycle Products Data
        $productsData = [
            // 1. Honda Dio Mudguard
            [
                'name' => 'Honda Dio 110 / 125 Front Mudguard (OEM Fit ABS)',
                'slug' => 'honda-dio-front-mudguard-oem-fit-abs',
                'category_id' => $categories['body-parts-and-mudguards']->id,
                'brand_id' => $brands['honda']->id,
                'sku' => 'DIO-MUD-01',
                'short_description' => 'Precision injection molded OEM replacement front mudguard designed for Honda Dio BS3, BS4, and BS6 models.',
                'description' => 'Restore your scooter\'s fresh showroom look with this direct OEM replacement front mudguard for Honda Dio 110/125. Manufactured from 100% virgin high-impact ABS plastic with factory UV-baked paint to resist cracking, stone chips, and sun fading. Exact factory mounting points guarantee effortless bolt-on installation without modifications.',
                'price' => 3850.00,
                'compare_price' => 4500.00,
                'stock' => 42,
                'thumbnail' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => true,
                'has_variants' => true,
                'attrs' => [
                    ['attr' => $attrVehicle, 'custom' => 'Honda Dio 110 (2012-2023), Honda Dio 125 (BS6 / H-Smart)'],
                    ['attr' => $attrMaterial, 'val' => $valABS],
                    ['attr' => $attrPosition, 'val' => $valFront],
                    ['attr' => $attrWarranty, 'custom' => '6 Months Color & Fitment Warranty'],
                ],
                'variants' => [
                    ['name' => 'Matte Axis Grey / Front', 'sku' => 'DIO-MUD-GRY', 'price' => 3850, 'stock' => 15, 'attrs' => ['Color' => 'Matte Axis Grey', 'Position' => 'Front']],
                    ['name' => 'Pearl Sports Yellow / Front', 'sku' => 'DIO-MUD-YEL', 'price' => 3950, 'stock' => 10, 'attrs' => ['Color' => 'Pearl Sports Yellow', 'Position' => 'Front']],
                    ['name' => 'Candy Blaze Red / Front', 'sku' => 'DIO-MUD-RED', 'price' => 3850, 'stock' => 9, 'attrs' => ['Color' => 'Candy Blaze Red', 'Position' => 'Front']],
                    ['name' => 'Matte Black / Front', 'sku' => 'DIO-MUD-BLK', 'price' => 3850, 'stock' => 8, 'attrs' => ['Color' => 'Matte Black', 'Position' => 'Front']],
                ],
            ],

            // 2. Suzuki Burgman Headlight Assembly
            [
                'name' => 'Suzuki Burgman Street 125 Full LED Headlight Assembly',
                'slug' => 'suzuki-burgman-street-125-full-led-headlight-assembly',
                'category_id' => $categories['lighting-and-electricals']->id,
                'brand_id' => $brands['suzuki']->id,
                'sku' => 'BRG-HDL-125',
                'short_description' => 'Complete OEM replacement full LED projector headlight assembly with integrated daytime running light (DRL) eyebrows for Suzuki Burgman Street 125.',
                'description' => 'Original factory specification Suzuki Burgman Street 125 LED Headlight Unit. Delivers ultra-wide beam pattern, crisp 6000K pure white illumination, and integrated aerodynamic casing. Includes factory sealed weatherproofing gaskets, scratch-resistant polycarbonate lens, and OEM plug-and-play wiring harness connector.',
                'price' => 16500.00,
                'compare_price' => 19200.00,
                'stock' => 14,
                'thumbnail' => 'https://images.unsplash.com/photo-1508974239320-0a029497e820?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => true,
                'has_variants' => false,
                'attrs' => [
                    ['attr' => $attrVehicle, 'custom' => 'Suzuki Burgman Street 125 (BS4 & BS6 All Editions)'],
                    ['attr' => $attrMaterial, 'val' => $valPolycarb],
                    ['attr' => $attrPosition, 'val' => $valFront],
                    ['attr' => $attrWarranty, 'custom' => '1 Year Official Electrical Warranty'],
                ],
            ],

            // 3. Suzuki Burgman Windshield Visor
            [
                'name' => 'Suzuki Burgman 125 Aerodynamic Tall Windshield Visor (Smoke Tint)',
                'slug' => 'suzuki-burgman-125-aerodynamic-tall-windshield-visor-smoke-tint',
                'category_id' => $categories['accessories-and-protection']->id,
                'brand_id' => $brands['suzuki']->id,
                'sku' => 'BRG-WND-02',
                'short_description' => '4mm heavy-duty tinted touring windshield designed to reduce highway wind buffeting, fatigue, and road spray.',
                'description' => 'Upgrade your touring comfort on the Burgman 125. Laser cut from optical-grade 4mm shatterproof polycarbonate with UV anti-glare smoke coating. Designed to guide air over the rider\'s helmet, drastically reducing wind noise and chest pressure on long highway rides.',
                'price' => 5200.00,
                'compare_price' => 6200.00,
                'stock' => 28,
                'thumbnail' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => true,
                'has_variants' => false,
                'attrs' => [
                    ['attr' => $attrVehicle, 'custom' => 'Suzuki Burgman Street 125 / EX 125'],
                    ['attr' => $attrColor, 'val' => $valSmokeTint],
                    ['attr' => $attrMaterial, 'val' => $valPolycarb],
                    ['attr' => $attrWarranty, 'custom' => '1 Year Anti-Cracking Guarantee'],
                ],
            ],

            // 4. Honda Dio CVT Drive Belt & Roller Weights
            [
                'name' => 'Honda Dio 110 Drive Belt & Variator Roller Weight Kit (Bando OEM)',
                'slug' => 'honda-dio-110-drive-belt-and-variator-roller-weight-kit',
                'category_id' => $categories['engine-and-transmission']->id,
                'brand_id' => $brands['bando']->id,
                'sku' => 'DIO-CVT-743',
                'short_description' => 'Heavy-duty Kevlar reinforced scooter transmission belt combined with a calibrated set of 6 variator roller weights.',
                'description' => 'Regain smooth acceleration, zero belt slip, and optimal fuel economy on your Honda Dio 110. Bando is the original factory OEM supplier for Honda scooters. Kevlar cord reinforcement ensures exceptional tensile strength and high resistance to heat degradation inside the CVT transmission case.',
                'price' => 4950.00,
                'compare_price' => 5800.00,
                'stock' => 35,
                'thumbnail' => 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => false,
                'has_variants' => true,
                'attrs' => [
                    ['attr' => $attrVehicle, 'custom' => 'Honda Dio 110, Honda Activa 110, Honda Aviator'],
                    ['attr' => $attrWarranty, 'custom' => '20,000 km Service Life Assurance'],
                ],
                'variants' => [
                    ['name' => 'Standard OEM 14g Weight Kit', 'sku' => 'DIO-CVT-14G', 'price' => 4950, 'stock' => 20, 'attrs' => ['Roller Weight' => '14 grams (OEM Balance)']],
                    ['name' => 'Performance 12g Quick Acceleration Kit', 'sku' => 'DIO-CVT-12G', 'price' => 5200, 'stock' => 15, 'attrs' => ['Roller Weight' => '12 grams (Fast Pick-up)']],
                ],
            ],

            // 5. TVS Ntorq LED Tail Light
            [
                'name' => 'TVS Ntorq 125 3D LED Signature Tail Light & Rear Mud Flap Assembly',
                'slug' => 'tvs-ntorq-125-3d-led-signature-tail-light-mud-flap-assembly',
                'category_id' => $categories['lighting-and-electricals']->id,
                'brand_id' => $brands['tvs']->id,
                'sku' => 'NTQ-TL-99',
                'short_description' => 'Original replacement rear tail lamp with signature \'T\' LED daytime running brake pattern, turn signals and number plate holder.',
                'description' => 'Direct replacement for broken or cracked rear tail assemblies on TVS Ntorq 125. Features the iconic jet-fighter inspired \'T\' signature LED glow, super-bright brake lights, internal turn indicator pods, and bottom license plate illumination lamp.',
                'price' => 7400.00,
                'compare_price' => 8600.00,
                'stock' => 19,
                'thumbnail' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => true,
                'has_variants' => false,
                'attrs' => [
                    ['attr' => $attrVehicle, 'custom' => 'TVS Ntorq 125 / Race Edition / XT / Super Squad'],
                    ['attr' => $attrPosition, 'val' => $valRear],
                    ['attr' => $attrWarranty, 'custom' => '6 Months Warranty'],
                ],
            ],

            // 6. Yamaha FZ / MT-15 Bi-LED Projector Headlight
            [
                'name' => 'Yamaha FZ / MT-15 Bi-LED Projector Headlight & Cowling Set',
                'slug' => 'yamaha-fz-mt-15-bi-led-projector-headlight-cowling-set',
                'category_id' => $categories['lighting-and-electricals']->id,
                'brand_id' => $brands['yamaha']->id,
                'sku' => 'YAM-FZ-PRJ',
                'short_description' => 'Ultra-bright 65W Bi-LED projector headlight assembly with menacing predator twin DRL strips.',
                'description' => 'Complete aggressive transformer-style headlight unit designed for Yamaha FZ-S V2/V3 and MT-15. Features a high-power center Bi-LED projector delivering 4500 Lumens on high beam, sharp cutoff low beam to prevent blinding oncoming drivers, and twin ice-blue / white LED parking guides.',
                'price' => 22000.00,
                'compare_price' => 25500.00,
                'stock' => 11,
                'thumbnail' => 'https://images.unsplash.com/photo-1508974239320-0a029497e820?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => true,
                'has_variants' => false,
                'attrs' => [
                    ['attr' => $attrVehicle, 'custom' => 'Yamaha FZ V2, FZ-S V3, MT-15 V1 & V2'],
                    ['attr' => $attrMaterial, 'val' => $valABS],
                    ['attr' => $attrPosition, 'val' => $valFront],
                    ['attr' => $attrWarranty, 'custom' => '1 Year Replacement Guarantee'],
                ],
            ],

            // 7. Brembo Front Brake Master Cylinder Set
            [
                'name' => 'Brembo Motorcycle Radial Front Brake Master Cylinder & Adjustable Lever Set',
                'slug' => 'brembo-motorcycle-radial-front-brake-master-cylinder-lever-set',
                'category_id' => $categories['brakes-and-suspension']->id,
                'brand_id' => $brands['brembo']->id,
                'sku' => 'BRM-MST-01',
                'short_description' => 'CNC machined radial piston brake pump with 6-click micro-adjustable foldaway lever for razor sharp braking response.',
                'description' => 'Forged aerospace alloy body with hard-anodized corrosion resistant surface. Delivers linear hydraulic pressure to disc calipers, providing effortless two-finger stopping power, improved lever feel, and reduced brake fade during high speed riding or heavy traffic.',
                'price' => 18500.00,
                'compare_price' => 21000.00,
                'stock' => 16,
                'thumbnail' => 'https://images.unsplash.com/photo-1600705722908-bab1e61c0b4d?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => true,
                'has_variants' => true,
                'attrs' => [
                    ['attr' => $attrVehicle, 'custom' => 'Universal 22mm (7/8") Handlebars - Honda Dio, Burgman, Pulsar, FZ, Apache, MT-15'],
                    ['attr' => $attrMaterial, 'val' => $valAlloy],
                    ['attr' => $attrWarranty, 'custom' => '1 Year Performance Guarantee'],
                ],
                'variants' => [
                    ['name' => 'Matte Black Anodized / 16mm Piston', 'sku' => 'BRM-MST-BLK', 'price' => 18500, 'stock' => 8, 'attrs' => ['Color' => 'Matte Black', 'Piston Size' => '16mm']],
                    ['name' => 'Titanium Grey Anodized / 16mm Piston', 'sku' => 'BRM-MST-GRY', 'price' => 18500, 'stock' => 5, 'attrs' => ['Color' => 'Titanium Grey', 'Piston Size' => '16mm']],
                    ['name' => 'Racing Gold Anodized / 16mm Piston', 'sku' => 'BRM-MST-GLD', 'price' => 19500, 'stock' => 3, 'attrs' => ['Color' => 'Racing Gold', 'Piston Size' => '16mm']],
                ],
            ],

            // 8. Bajaj Pulsar Caliper & Sintered Brake Pads
            [
                'name' => 'Bajaj Pulsar 150 / 180 / 220 Front Brake Caliper & Sintered Ceramic Pad Set',
                'slug' => 'bajaj-pulsar-front-brake-caliper-and-sintered-ceramic-pad-set',
                'category_id' => $categories['brakes-and-suspension']->id,
                'brand_id' => $brands['bajaj']->id,
                'sku' => 'PUL-BRK-220',
                'short_description' => 'Dual-piston OEM front disc brake caliper assembly pre-fitted with high-friction copper sintered ceramic pads.',
                'description' => 'Complete replacement front brake caliper unit for Bajaj Pulsar series. Manufactured under strict ISO standards to prevent fluid leaks, seized pistons, or uneven disc wear. High-temp ceramic pads provide instant bite with zero squeal.',
                'price' => 6800.00,
                'compare_price' => 7900.00,
                'stock' => 24,
                'thumbnail' => 'https://images.unsplash.com/photo-1600705722908-bab1e61c0b4d?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => false,
                'has_variants' => false,
                'attrs' => [
                    ['attr' => $attrVehicle, 'custom' => 'Bajaj Pulsar 150, Pulsar 180, Pulsar 220F, Pulsar NS200'],
                    ['attr' => $attrPosition, 'val' => $valFront],
                    ['attr' => $attrWarranty, 'custom' => '15,000 km Mileage Warranty'],
                ],
            ],

            // 9. NGK Iridium IX Spark Plug
            [
                'name' => 'NGK Iridium IX Motorcycle Spark Plug (CPR8EAIX-9)',
                'slug' => 'ngk-iridium-ix-motorcycle-spark-plug-cpr8eaix-9',
                'category_id' => $categories['maintenance-and-lubricants']->id,
                'brand_id' => $brands['ngk']->id,
                'sku' => 'NGK-IRD-CPR8',
                'short_description' => '0.6mm laser welded ultra-fine iridium center electrode designed for maximum ignition spark, fuel economy, and anti-fouling.',
                'description' => 'Transform your engine\'s responsiveness and cold-morning start performance with genuine NGK Iridium IX. Delivers higher spark energy at lower voltage demands, completely burning the fuel-air mixture to eliminate throttle hesitation and improve kilometers-per-liter.',
                'price' => 2450.00,
                'compare_price' => 2900.00,
                'stock' => 60,
                'thumbnail' => 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => true,
                'has_variants' => false,
                'attrs' => [
                    ['attr' => $attrVehicle, 'custom' => 'Honda Dio, Suzuki Burgman, TVS Ntorq, Yamaha FZ, Bajaj Pulsar'],
                    ['attr' => $attrWarranty, 'custom' => '40,000 km Guaranteed Longevity'],
                ],
            ],

            // 10. Heavy-Duty Scooter & Motorbike Crash Guard
            [
                'name' => 'Universal Heavy-Duty Scooter & Motorcycle Engine Crash Guard with Slider Bungs',
                'slug' => 'universal-heavy-duty-scooter-motorcycle-engine-crash-guard-slider',
                'category_id' => $categories['accessories-and-protection']->id,
                'brand_id' => $brands['endurance']->id,
                'sku' => 'ACC-CRSH-UNIV',
                'short_description' => 'Heavy-gauge seamless steel tube protective guard equipped with impact-absorbing nylon sliders and fog light mounts.',
                'description' => 'Protect your expensive scooter body panels, engine crankcase, and footboards against accidental drops and scrapes. Heavy-duty powder-coated steel frame absorbs and redistributes collision impact force. Includes CNC machined Delrin slider pucks.',
                'price' => 8900.00,
                'compare_price' => 10500.00,
                'stock' => 18,
                'thumbnail' => 'https://images.unsplash.com/photo-1558980664-769d59546b3d?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => true,
                'has_variants' => false,
                'attrs' => [
                    ['attr' => $attrVehicle, 'custom' => 'Honda Dio 110/125, TVS Ntorq, Suzuki Burgman, Yamaha RayZR'],
                    ['attr' => $attrColor, 'val' => $valMatteBlack],
                    ['attr' => $attrWarranty, 'custom' => '2 Years Structural Weld Guarantee'],
                ],
            ],

            // 11. Honda Dio / Activa Keihin Type Carburetor
            [
                'name' => 'Honda Dio / Activa Keihin Type Performance Carburetor Assembly',
                'slug' => 'honda-dio-activa-keihin-type-carburetor-assembly',
                'category_id' => $categories['engine-and-transmission']->id,
                'brand_id' => $brands['honda']->id,
                'sku' => 'DIO-CRB-110',
                'short_description' => 'Factory calibrated carburetor with integrated electric auto-choke solenoid for instant starts and steady idle.',
                'description' => 'Precision CNC fuel jets ensure optimal atomization and balanced air-fuel ratio. Resolves common problems like morning starting issues, erratic idling, engine stalling at traffic stops, and fuel overflowing.',
                'price' => 8200.00,
                'compare_price' => 9500.00,
                'stock' => 22,
                'thumbnail' => 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => false,
                'has_variants' => false,
                'attrs' => [
                    ['attr' => $attrVehicle, 'custom' => 'Honda Dio (BS3/BS4 110cc), Honda Activa 110, Honda Aviator'],
                    ['attr' => $attrWarranty, 'custom' => '1 Year Performance Warranty'],
                ],
            ],

            // 12. Motul 7100 4T 10W-40 Fully Synthetic Engine Oil
            [
                'name' => 'Motul 7100 4T 10W-40 100% Fully Synthetic Motorcycle Engine Oil (1L)',
                'slug' => 'motul-7100-4t-10w40-fully-synthetic-motorcycle-engine-oil-1l',
                'category_id' => $categories['maintenance-and-lubricants']->id,
                'brand_id' => $brands['motul']->id,
                'sku' => 'MTL-7100-10W40',
                'short_description' => 'Ester technology formulation delivering unmatched anti-wear protection, smooth gear shifts, and high temperature stability.',
                'description' => 'Meets API SN / JASO MA2 standards. 100% synthetic Ester lubricant formulated specifically for modern high-revving 4-stroke motorcycle and scooter engines. Keeps pistons and valves ultra-clean, prevents clutch slippage, and extends engine component lifespan.',
                'price' => 5600.00,
                'compare_price' => 6200.00,
                'stock' => 50,
                'thumbnail' => 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => true,
                'has_variants' => false,
                'attrs' => [
                    ['attr' => $attrVehicle, 'custom' => 'All 4-Stroke Scooters & Bikes (Honda, Yamaha, Suzuki, TVS, Bajaj, KTM)'],
                    ['attr' => $attrWarranty, 'custom' => '100% Genuine Sealed Bottle Guarantee'],
                ],
            ],

            // 13. All-Weather 3D Anti-Slip Seat Cover
            [
                'name' => 'All-Weather 3D Honeycomb Anti-Slip Motorcycle & Scooter Seat Cushion Cover',
                'slug' => 'all-weather-3d-honeycomb-anti-slip-seat-cushion-cover',
                'category_id' => $categories['accessories-and-protection']->id,
                'brand_id' => $brands['honda']->id,
                'sku' => 'ACC-SET-001',
                'short_description' => 'Breathable 8mm dual-layer honeycomb mesh cover that prevents seat heat build-up under direct sunlight and drains rainwater instantly.',
                'description' => 'Say goodbye to burning hot motorcycle seats! The 3D micro-spring mesh creates a continuous cooling air gap between your body and the seat. Elastic perimeter velcro straps allow easy 2-minute DIY installation without stapling.',
                'price' => 1650.00,
                'compare_price' => 2100.00,
                'stock' => 45,
                'thumbnail' => 'https://images.unsplash.com/photo-1558980664-769d59546b3d?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => false,
                'has_variants' => true,
                'attrs' => [
                    ['attr' => $attrMaterial, 'val' => $valABS],
                    ['attr' => $attrColor, 'val' => $valMatteBlack],
                    ['attr' => $attrWarranty, 'custom' => '6 Months Durability Guarantee'],
                ],
                'variants' => [
                    ['name' => 'Size M (Scooters: Honda Dio / Burgman / Activa / Ntorq)', 'sku' => 'ACC-SET-SCOT', 'price' => 1650, 'stock' => 25, 'attrs' => ['Size' => 'Medium (Scooters)']],
                    ['name' => 'Size L (Bikes: Yamaha FZ / Pulsar / Apache / MT-15)', 'sku' => 'ACC-SET-BIKE', 'price' => 1850, 'stock' => 20, 'attrs' => ['Size' => 'Large (Motorcycles)']],
                ],
            ],

            // 14. Yamaha RayZR 125 Side Body Panel Set
            [
                'name' => 'Yamaha RayZR 125 Street Rally Front Cowling & Side Body Panel Kit',
                'slug' => 'yamaha-rayzr-125-front-cowling-side-body-panel-kit',
                'category_id' => $categories['body-parts-and-mudguards']->id,
                'brand_id' => $brands['yamaha']->id,
                'sku' => 'YAM-RAY-PNL',
                'short_description' => 'Complete aerodynamic side body wing panels and front meter visor kit for Yamaha RayZR 125 Fi.',
                'description' => 'Genuine Yamaha replacement body kit manufactured with tough, shatter-resistant polymers. Replaces scratched or damaged side skirts and front cowl sections. Precision pre-drilled clips and screw bosses align perfectly with OEM chassis brackets.',
                'price' => 12800.00,
                'compare_price' => 14500.00,
                'stock' => 8,
                'thumbnail' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'is_featured' => true,
                'has_variants' => false,
                'attrs' => [
                    ['attr' => $attrVehicle, 'custom' => 'Yamaha RayZR 125 Fi / RayZR Street Rally Hybrid'],
                    ['attr' => $attrMaterial, 'val' => $valABS],
                    ['attr' => $attrPosition, 'val' => $valFullSet],
                    ['attr' => $attrWarranty, 'custom' => '1 Year Factory Fitment Warranty'],
                ],
            ],
        ];

        foreach ($productsData as $pData) {
            $attrs = $pData['attrs'] ?? [];
            $variants = $pData['variants'] ?? [];
            unset($pData['attrs'], $pData['variants']);

            $product = Product::create($pData);

            // Add gallery image
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
            'code' => 'MOTO10',
            'type' => 'percentage',
            'value' => 10,
            'min_spend' => 3000,
            'max_discount' => 3000,
            'usage_limit' => 500,
            'used_count' => 28,
            'expires_at' => now()->addMonths(6),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'RIDE1000',
            'type' => 'fixed',
            'value' => 1000,
            'min_spend' => 10000,
            'usage_limit' => 100,
            'used_count' => 12,
            'expires_at' => now()->addMonths(3),
            'is_active' => true,
        ]);

        // 8. Realistic Sample Orders
        $order1 = Order::create([
            'order_number' => 'ORD-2026-000201',
            'user_id' => $customer->id,
            'customer_name' => 'Kasun Perera',
            'customer_email' => 'customer@example.com',
            'customer_phone' => '077 123 4567',
            'shipping_address' => 'No 88/2, High Level Road, Maharagama',
            'city' => 'Colombo',
            'state' => 'Western Province',
            'postal_code' => '10280',
            'payment_method' => 'card',
            'payment_status' => 'paid',
            'status' => 'delivered',
            'subtotal' => 20350.00,
            'discount' => 2035.00,
            'coupon_code' => 'MOTO10',
            'shipping_fee' => 0.00,
            'total' => 18315.00,
            'notes' => 'Please deliver in bubble wrap packaging for plastic mudguard.',
        ]);

        $dioMudguard = Product::where('sku', 'DIO-MUD-01')->first();
        if ($dioMudguard) {
            OrderItem::create([
                'order_id' => $order1->id,
                'product_id' => $dioMudguard->id,
                'product_name' => $dioMudguard->name,
                'variant_name' => 'Matte Axis Grey / Front',
                'sku' => 'DIO-MUD-GRY',
                'price' => 3850.00,
                'quantity' => 1,
                'total' => 3850.00,
                'attributes_snapshot' => ['Color' => 'Matte Axis Grey', 'Position' => 'Front'],
            ]);
        }

        $burgmanLight = Product::where('sku', 'BRG-HDL-125')->first();
        if ($burgmanLight) {
            OrderItem::create([
                'order_id' => $order1->id,
                'product_id' => $burgmanLight->id,
                'product_name' => $burgmanLight->name,
                'sku' => $burgmanLight->sku,
                'price' => 16500.00,
                'quantity' => 1,
                'total' => 16500.00,
            ]);
        }

        $order2 = Order::create([
            'order_number' => 'ORD-2026-000202',
            'user_id' => null, // Guest Checkout
            'customer_name' => 'Sanjaya Bandara',
            'customer_email' => 'sanjaya.moto@gmail.com',
            'customer_phone' => '071 889 9123',
            'shipping_address' => 'No. 15, Kandy Road, Kiribathgoda',
            'city' => 'Gampaha',
            'state' => 'Western Province',
            'postal_code' => '11600',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'status' => 'processing',
            'subtotal' => 7400.00,
            'discount' => 0.00,
            'shipping_fee' => 450.00,
            'total' => 7850.00,
            'notes' => 'Call on mobile before arrival.',
        ]);

        $ntorqLight = Product::where('sku', 'NTQ-TL-99')->first();
        if ($ntorqLight) {
            OrderItem::create([
                'order_id' => $order2->id,
                'product_id' => $ntorqLight->id,
                'product_name' => $ntorqLight->name,
                'sku' => $ntorqLight->sku,
                'price' => 7400.00,
                'quantity' => 1,
                'total' => 7400.00,
            ]);
        }
    }
}
