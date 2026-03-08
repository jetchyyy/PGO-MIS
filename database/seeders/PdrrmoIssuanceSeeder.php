<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\FundCluster;
use App\Models\Item;
use App\Models\Office;
use App\Models\PropertyTransaction;
use App\Models\PropertyTransactionLine;
use App\Models\User;
use App\Support\NumberGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PdrrmoIssuanceSeeder extends Seeder
{
    /**
     * Item catalog lookup: description keyword → Item.name
     * Used to link each issuance line to the item catalog.
     */
    private const ITEM_MAP = [
        'Water Tanker'                        => 'Customized 4000L Water Tanker',
        'Forklift'                            => 'Brand New Forklift',
        'Mass Evacuation Vehicle'             => 'Customized Mass Evacuation Vehicle (HINO 300)',
        'Rescue Truck'                        => 'Rescue Truck with Crane, Rescue Tools and Equipment',
        'Yamaha YTX125'                       => 'Motorcycle Yamaha YTX125',
        'Honda ADV160'                        => 'Honda ADV160 Response/Rescue Motorcycle',
        'Sea Ambulance'                       => 'Sea Ambulance with Accessories',
        'Rapid Response'                      => '4X4 M/T Rapid Response Rescue Vehicle',
        'ThinkPad P1'                         => 'Lenovo ThinkPad P1 Gen 7 Laptop',
        'Office Furniture'                    => 'Office Furniture (Workstation-Fishbone 4-Seater)',
        'MacBook Pro'                         => 'MacBook Pro 14-inch M3 Laptop',
        'iPad Pro'                            => 'iPad Pro 13-inch M4 Tablet',
        'Video Conference System'             => 'Video Conference System (Logitech Rally Plus)',
        'LEO Satellite Internet'              => 'LEO Satellite Internet (Standard Plan)',
        'Refrigerator'                        => '2-Door Top Mount Freezer Refrigerator',
        'Rescue Boat'                         => 'Rescue Boat',
        'Fire Truck'                          => '6-Wheels 2500-L PTO Fire Truck',
        'Toyota HILUX'                        => 'Toyota HILUX Pick-up',
        'Toyota HIACE'                        => 'Toyota HIACE Commuter Ambulance',
        'Honda TMX125'                        => 'Honda TMX125 Motorcycle',
        'Honda XRM125'                        => 'Honda XRM125 MSE Motorcycle',
        '100kVA Generator'                    => '100kVA Generator Set',
        '50kVA Generator'                     => '50kVA Generator Set',
        'Chainsaw'                            => 'Chainsaw',
        'Nissan NV350'                        => 'Nissan NV350 Urvan Ambulance',
        'Nissan Ambulance NV350'              => 'Nissan NV350 Urvan Ambulance',
        'Suzuki APV'                          => 'Suzuki APV GA-MT Van',
        'Digital Fixed Base Radio Augmentation'=> 'Digital Fixed Base Radio Augmentation (Motorola XiR M6660)',
        'Digital Portable Radio'              => 'Digital Portable Radio (Motorola Mototrbo R7 VHF)',
        'Video Wall Augmentation'             => 'Video Wall Augmentation (LED Monitors)',
        'IP Telephony'                        => 'IP Telephony Equipment with Voice Recording',
        'Desktop Terminal Set'                => 'Desktop Terminal Set (Dell Optiplex 3000 SFF)',
        'Firewall Equipment'                  => 'Firewall Equipment (Netgate 6100 Base)',
        'Managed Switch'                      => '24 Port Layer 3 Managed Switch',
        'Incident Management System'          => 'Incident Management System (Bayan 911)',
        'Computer Aided Dispatch'             => 'Computer Aided Dispatch System',
        'Network UPS'                         => 'Network UPS (APC Smart UPS 3000VA)',
        'Equipment Rack'                      => 'Equipment Rack Cabinet',
        'Lenovo Slim'                         => 'Lenovo Slim 5i Ideapad Laptop',
        'Structured Cabling'                  => 'Structured Cabling & LAN Surveillance System',
        'Videowall Display'                   => 'Videowall Display System (Dahua)',
        'PTZ Camera'                          => 'PTZ Camera 45x Optical Zoom',
        'Network Video Recorder'              => 'Network Video Recorder',
        'Rackmount UPS'                       => 'Rackmount UPS (KEBOS GR11-3kVA)',
        '55-inch LED'                         => '55-inch LED Television (Samsung 4K UHD)',
        'Bi-pod Pole Mount'                   => 'Bi-pod Pole Mount (10m) Digital Two-way Radio',
        'Digital Two-way Radio Repeater'      => 'Digital Two-way Radio Repeater System',
        'Digital Fixed Base Radio VHF 45W'    => 'Digital Fixed Base Radio VHF 45W Set w/ Backup Power',
        'Digital Fixed Base Radio Set'        => 'Digital Fixed Base Radio Set (Motorola XiR-M8668i VHF)',
        'High Capacity LEO Satellite'         => 'High Capacity LEO Satellite Connection with Managed WiFi',
        'Standard Capacity LEO Satellite'     => 'Standard Capacity LEO Satellite Connection with Managed WiFi',
        'Centralized Software'                => 'Centralized Software-based Controller',
        'GSM Base Transceiver'                => 'GSM Base Transceiver Station',
        'Telemetry Remote Terminal'           => 'Telemetry Remote Terminal Unit (RTU) Set',
        'Telemetry Platform'                  => 'Telemetry Platform and Notification System',
        'Desktop Computer'                    => 'Desktop Computer',
        'Conference Table'                    => 'Conference Table (12-Seater)',
        'Workstation Cubicle'                 => '2-Staff Dynamic Office Workstation Cubicle with Desk',
        'Solar Panel 30W'                     => 'Solar Panel 30W (Tsunami Early Warning)',
        '4.0HP'                               => '4.0HP (3TR) Floor Mounted Inverter Aircon',
        '2.0HP'                               => '2.0HP Super Inverter Lunaire Series Aircon',
        '2.5HP'                               => '2.5HP Super Inverter Lunaire Series Aircon',
        'Hybrid Inverter'                     => '16kW Hybrid Inverter',
        'Lithium Battery'                     => 'Lithium Battery 300AH 51.2V',
    ];

    /**
     * Resolve an item_id from the catalog by matching description keywords.
     */
    private function resolveItemId(string $description, array $itemsByName): ?int
    {
        foreach (self::ITEM_MAP as $keyword => $itemName) {
            if (stripos($description, $keyword) !== false && isset($itemsByName[$itemName])) {
                return $itemsByName[$itemName];
            }
        }

        return null;
    }

    public function run(): void
    {
        // ──────────────────────────────────────────────
        // 1. Create PDRRMO Office
        // ──────────────────────────────────────────────
        $pdrrmo = Office::firstOrCreate(
            ['code' => 'PDRRMO'],
            ['name' => 'Provincial Disaster Risk Reduction and Management Office']
        );

        // ──────────────────────────────────────────────
        // 2. Fund cluster
        // ──────────────────────────────────────────────
        $fc = FundCluster::firstOrCreate(
            ['code' => 'FC-01'],
            ['name' => 'General Fund']
        );

        // ──────────────────────────────────────────────
        // 3. Create employees
        // ──────────────────────────────────────────────
        $employees = [];
        $empData = [
            'Albert B. Lumapas'         => ['designation' => 'PDRRMO Staff',       'station' => 'PDRRMO'],
            'Cicero Cosme P. Tripoli'   => ['designation' => 'PDRRMO Staff',       'station' => 'PDRRMO'],
            'Gilberto L. Gonzales'      => ['designation' => 'PDRRMO Staff',       'station' => 'PDRRMO'],
            'Rasphem R. Luib'           => ['designation' => 'PDRRMO Staff',       'station' => 'PDRRMO'],
            'Edgar B. Catulay'          => ['designation' => 'PDRRMO Staff',       'station' => 'PDRRMO'],
            'Rowelto E. Dumale Jr.'     => ['designation' => 'PDRRMO Staff',       'station' => 'PDRRMO'],
            'Candice Danica A. Yuipco'  => ['designation' => 'PDRRMO Staff',       'station' => 'PDRRMO'],
            'Gilbert O. Calvadores'     => ['designation' => 'PDRRMO Staff',       'station' => 'PDRRMO'],
        ];

        foreach ($empData as $name => $info) {
            $employees[$name] = Employee::firstOrCreate(
                ['name' => $name],
                [
                    'office_id'   => $pdrrmo->id,
                    'designation' => $info['designation'],
                    'station'     => $info['station'],
                ]
            );
        }

        // System user for created_by
        $adminUser = User::where('role', 'system_admin')->first();
        $createdBy = $adminUser ? $adminUser->id : 1;

        // Preload item catalog keyed by name → id
        $itemsByName = Item::pluck('id', 'name')->toArray();

        $entityName = 'Provincial Government of Surigao del Norte';

        // ──────────────────────────────────────────────
        // 4. Define all issuance transactions
        //    Grouped by accountable person + approximate date
        // ──────────────────────────────────────────────
        $issuances = [
            // ═══════════════════════════════════════
            // Albert B. Lumapas — undated items
            // ═══════════════════════════════════════
            [
                'employee' => 'Albert B. Lumapas',
                'date' => null, // no date acquired — use transaction date
                'transaction_date' => '2024-01-01',
                'lines' => [
                    [
                        'description' => 'Customized 4000L Water Tanker — CAB AND CHASIS: G.V.W: 4,490kg, Euro 4, Diesel Turbo-charged & Intercooled, 4.009L, 100L Fuel Tank, 7.00R16-14PR',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 9326400.00,
                        'property_no' => null,
                        'date_acquired' => null,
                        'estimated_useful_life' => '15 years',
                    ],
                ],
            ],

            // Albert B. Lumapas — 2/10/2025 (Forklifts)
            [
                'employee' => 'Albert B. Lumapas',
                'transaction_date' => '2025-02-10',
                'lines' => [
                    [
                        'description' => 'Brand New Forklift (Engine No. Q240777855H)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 1750000.00,
                        'property_no' => '10705080-001-FRKL010303J7741',
                        'date_acquired' => '2025-02-10',
                        'estimated_useful_life' => '10 years',
                    ],
                    [
                        'description' => 'Brand New Forklift (Engine No. Q240777856H)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 1750000.00,
                        'property_no' => '10705080-001-FRKL010303J7739',
                        'date_acquired' => '2025-02-10',
                        'estimated_useful_life' => '10 years',
                    ],
                ],
            ],

            // Albert B. Lumapas — 12/23/2024 (HINO 300 Evacuation Vehicles)
            [
                'employee' => 'Albert B. Lumapas',
                'transaction_date' => '2024-12-23',
                'lines' => [
                    [
                        'description' => 'Customized Mass Evacuation Vehicle (HINO 300) — Engine No. N04CWK23581',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 5499000.00,
                        'property_no' => '10706010-001-SZA-1170',
                        'date_acquired' => '2024-12-23',
                        'estimated_useful_life' => '15 years',
                    ],
                    [
                        'description' => 'Customized Mass Evacuation Vehicle (HINO 300) — Engine No. N04CWK23584',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 5499000.00,
                        'property_no' => '10706010-001-SZA-1171',
                        'date_acquired' => '2024-12-23',
                        'estimated_useful_life' => '15 years',
                    ],
                ],
            ],

            // Albert B. Lumapas — 8/5/2024 (Rescue Trucks)
            [
                'employee' => 'Albert B. Lumapas',
                'transaction_date' => '2024-08-05',
                'lines' => [
                    [
                        'description' => 'Rescue Truck with crane, rescue tools and equipment — Model: HINO XZU730LN, Engine No. N04CWK22405',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 9998500.00,
                        'property_no' => '107046010-001-JOK-369',
                        'date_acquired' => '2024-08-05',
                        'estimated_useful_life' => '15 years',
                    ],
                    [
                        'description' => 'Rescue Truck with crane, rescue tools and equipment — Model: HINO XZU730LN',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 9998500.00,
                        'property_no' => '10706010-001-JOK-489',
                        'date_acquired' => '2024-08-05',
                        'estimated_useful_life' => '15 years',
                    ],
                ],
            ],

            // Albert B. Lumapas — 9/8/2021 (Yamaha Motorcycles)
            [
                'employee' => 'Albert B. Lumapas',
                'transaction_date' => '2021-09-08',
                'lines' => [
                    [
                        'description' => 'Yamaha YTX125 Motorcycle (C#PA0RE3210M0194088)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 86666.66,
                        'property_no' => '10706010-002-YMH-0067',
                        'date_acquired' => '2021-09-08',
                        'estimated_useful_life' => '10 years',
                    ],
                    [
                        'description' => 'Yamaha YTX125 Motorcycle (C#PA0RE3210M0191200)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 86666.66,
                        'property_no' => '10706010-002-YMH-0106',
                        'date_acquired' => '2021-09-08',
                        'estimated_useful_life' => '10 years',
                    ],
                    [
                        'description' => 'Yamaha YTX125 Motorcycle (C#PA0RE3210M0192215)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 86666.66,
                        'property_no' => '10706010-002-YMH-0110',
                        'date_acquired' => '2021-09-08',
                        'estimated_useful_life' => '10 years',
                    ],
                ],
            ],

            // Albert B. Lumapas — Honda ADV160 (undated)
            [
                'employee' => 'Albert B. Lumapas',
                'transaction_date' => '2025-01-15',
                'lines' => array_map(fn ($i) => [
                    'description' => "Honda ADV160 Response/Rescue Motorcycle (Unit $i of 15) — Engine: KF51E7125620 series",
                    'quantity' => 1,
                    'unit' => 'unit',
                    'unit_cost' => 278500.00,
                    'property_no' => "10706010-022-KF51E-" . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'date_acquired' => null,
                    'estimated_useful_life' => '10 years',
                ], range(1, 15)),
            ],

            // ═══════════════════════════════════════
            // Cicero Cosme P. Tripoli — 7/25/2024 (Sea Ambulance)
            // ═══════════════════════════════════════
            [
                'employee' => 'Cicero Cosme P. Tripoli',
                'transaction_date' => '2024-07-25',
                'lines' => [
                    [
                        'description' => 'Sea Ambulance with Accessories — Overall Length: 8.80m, Beam: 2.64m, Engine: 150 HP (Twin), Capacity: 12 Passengers, Fuel Tank: 300L',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 9990000.00,
                        'property_no' => '10706040-001-PBS-0013',
                        'date_acquired' => '2024-07-25',
                        'estimated_useful_life' => '15 years',
                    ],
                ],
            ],

            // Cicero Cosme P. Tripoli — 6/14/2024 (Rapid Response Vehicle)
            [
                'employee' => 'Cicero Cosme P. Tripoli',
                'transaction_date' => '2024-06-14',
                'lines' => [
                    [
                        'description' => '4X4 M/T Rapid Response Rescue Vehicle — Serial No. KC 154A, Engine No. 2GDD402041',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 2998000.00,
                        'property_no' => '10706010-001-0801-00000568074',
                        'date_acquired' => '2024-06-14',
                        'estimated_useful_life' => '15 years',
                    ],
                ],
            ],

            // ═══════════════════════════════════════
            // Cicero Cosme P. Tripoli — 5/13/2025 (IT & Office Equipment)
            // ═══════════════════════════════════════
            [
                'employee' => 'Cicero Cosme P. Tripoli',
                'transaction_date' => '2025-05-13',
                'lines' => [
                    [
                        'description' => 'Lenovo ThinkPad P1 Gen 7 Laptop — P-cores up to 4.80 GHz, 32GB LPDDR5X, Windows 11 Home 64, w/ ThinkPad Essential Plus 15.6" Backpack',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 185850.45,
                        'property_no' => '10705030-001-LAP-0852',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                    ],
                    [
                        'description' => 'Office Furniture Set — Workstation-Fishbone 4-seater H120xW120xD60cm, Executive Table W120xD60xH75cm, Midback chair, Conference table',
                        'quantity' => 1,
                        'unit' => 'lot',
                        'unit_cost' => 319570.00,
                        'property_no' => '10707010-001-ConfS-001',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '10 years',
                    ],
                    [
                        'description' => 'MacBook Pro 14-inch M3 Laptop — 12-core CPU, 18-core GPU, 18GB Unified Memory, 1TB SSD (SN: HGG2PV37HP)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 188788.20,
                        'property_no' => '10705030-001-LAP-0853',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                    ],
                    [
                        'description' => 'iPad Pro 13-inch M4 Tablet — 256GB WiFi (SN: CMJK7MGXNL)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 112088.20,
                        'property_no' => '10705030-001-Tab-0095',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                    ],
                    [
                        'description' => 'iPad Pro 13-inch M4 Tablet — 256GB WiFi (SN: GDWQYWT26X)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 112088.20,
                        'property_no' => '10705030-001-Tab-0096',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                    ],
                    [
                        'description' => 'Video Conference System — Logitech Rally Plus PTZ Camera, 4K UHD, 13MP, 15x HD Zoom, 2 Microphones (SN: 2446ZBE0MDC9)',
                        'quantity' => 1,
                        'unit' => 'lot',
                        'unit_cost' => 347077.50,
                        'property_no' => '10705030-003-VidConS-001',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                    ],
                    [
                        'description' => 'LEO Satellite Internet Access — Standard service plan, 1 year tech support w/ ethernet adapter',
                        'quantity' => 1,
                        'unit' => 'lot',
                        'unit_cost' => 175980.00,
                        'property_no' => '10705070-999-SatLeo-001',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                    ],
                ],
            ],

            // Cicero Cosme P. Tripoli — 2/28/2025 (Refrigerator)
            [
                'employee' => 'Cicero Cosme P. Tripoli',
                'transaction_date' => '2025-02-28',
                'lines' => [
                    [
                        'description' => '2-Door Top Mount Freezer Refrigerator — 8.0 cu.ft, 225L gross, Inverter Technology, DoorCooling+, 230V/60Hz, R600a',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 50000.00,
                        'property_no' => null,
                        'date_acquired' => '2025-02-28',
                        'estimated_useful_life' => '10 years',
                    ],
                ],
            ],

            // ═══════════════════════════════════════
            // Gilberto L. Gonzales — 12/27/2019 (Rescue Boats)
            // ═══════════════════════════════════════
            [
                'employee' => 'Gilberto L. Gonzales',
                'transaction_date' => '2019-12-27',
                'lines' => [
                    [
                        'description' => 'Brand New Rescue Boat (Unit 1)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 1300000.00,
                        'property_no' => '10705090-001-RGE-0009',
                        'date_acquired' => '2019-12-27',
                        'estimated_useful_life' => '15 years',
                    ],
                    [
                        'description' => 'Brand New Rescue Boat (Unit 2)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 1300000.00,
                        'property_no' => '10705090-001-RGE-0010',
                        'date_acquired' => '2019-12-27',
                        'estimated_useful_life' => '15 years',
                    ],
                ],
            ],

            // Gilberto L. Gonzales — 12/28/2018 (Fire Truck)
            [
                'employee' => 'Gilberto L. Gonzales',
                'transaction_date' => '2018-12-28',
                'lines' => [
                    [
                        'description' => '6-Wheels 2500-L PTO Fire Truck — Plate/MV File No. NBE-6775',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 4395000.00,
                        'property_no' => '10705090-001-Ftr-0001',
                        'date_acquired' => '2018-12-28',
                        'estimated_useful_life' => '15 years',
                    ],
                ],
            ],

            // Gilberto L. Gonzales — 10/23/2018 (Toyota Hilux)
            [
                'employee' => 'Gilberto L. Gonzales',
                'transaction_date' => '2018-10-23',
                'lines' => [
                    [
                        'description' => 'Toyota HILUX Pick-up — Plate/MV File No. SAA-689',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 1723217.00,
                        'property_no' => '241-003-SAA-689',
                        'date_acquired' => '2018-10-23',
                        'estimated_useful_life' => '15 years',
                    ],
                ],
            ],

            // Gilberto L. Gonzales — 6/13/2017 (Toyota Hiace Ambulance)
            [
                'employee' => 'Gilberto L. Gonzales',
                'transaction_date' => '2017-06-13',
                'lines' => [
                    [
                        'description' => 'Toyota HIACE Commuter Ambulance — Engine No. 1KD2668B279',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 1759556.00,
                        'property_no' => '241-003-SAA-6859',
                        'date_acquired' => '2017-06-13',
                        'estimated_useful_life' => '15 years',
                    ],
                ],
            ],

            // Gilberto L. Gonzales — 6/6/2016 (Honda TMX125 x2 — For Disposal)
            [
                'employee' => 'Gilberto L. Gonzales',
                'transaction_date' => '2016-06-06',
                'lines' => [
                    [
                        'description' => 'Honda TMX125 Motorcycle (Black) — C#: KSW00117005',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 113800.00,
                        'property_no' => '241-002-KSW00E117451',
                        'date_acquired' => '2016-06-06',
                        'estimated_useful_life' => '10 years',
                        'remarks' => 'For Disposal / Obsolete',
                    ],
                    [
                        'description' => 'Honda TMX125 Motorcycle (Red) — C#: KSW00117005',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 113800.00,
                        'property_no' => '241-002-KSW00E124275',
                        'date_acquired' => '2016-06-06',
                        'estimated_useful_life' => '10 years',
                        'remarks' => 'For Disposal / Obsolete',
                    ],
                ],
            ],

            // Gilberto L. Gonzales — 12/29/2015 (Honda XRM125 — For Disposal)
            [
                'employee' => 'Gilberto L. Gonzales',
                'transaction_date' => '2015-12-29',
                'lines' => [
                    [
                        'description' => 'Honda XRM125 MSE Motorcycle — C#: KPY00113686',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 64460.00,
                        'property_no' => '241-002-TEMP-1013',
                        'date_acquired' => '2015-12-29',
                        'estimated_useful_life' => '10 years',
                        'remarks' => 'For Disposal / Obsolete',
                    ],
                ],
            ],

            // Gilberto L. Gonzales — 12/27/2024 (Generator Sets)
            [
                'employee' => 'Gilberto L. Gonzales',
                'transaction_date' => '2024-12-27',
                'lines' => [
                    [
                        'description' => '100kVA Generator Set',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 1800000.00,
                        'property_no' => '10703050-002-GenS-0016',
                        'date_acquired' => '2024-12-27',
                        'estimated_useful_life' => '15 years',
                    ],
                    [
                        'description' => '50kVA Generator Set',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 1480000.00,
                        'property_no' => '10703050-002-GenS-0015',
                        'date_acquired' => '2024-12-27',
                        'estimated_useful_life' => '15 years',
                    ],
                ],
            ],

            // Gilberto L. Gonzales — 2/26/2024 (Chainsaws)
            [
                'employee' => 'Gilberto L. Gonzales',
                'transaction_date' => '2024-02-26',
                'lines' => array_map(fn ($i) => [
                    'description' => "Chainsaw (Unit $i of 5)",
                    'quantity' => 1,
                    'unit' => 'pc',
                    'unit_cost' => 53000.00,
                    'property_no' => null,
                    'date_acquired' => '2024-02-26',
                    'estimated_useful_life' => '5 years',
                ], range(1, 5)),
            ],

            // ═══════════════════════════════════════
            // Nissan Ambulances — 5/17/2019 & 10/26/2018 (no specific accountable person for some)
            // ═══════════════════════════════════════
            [
                'employee' => 'Albert B. Lumapas', // defaulting to PDRRMO head of inventory
                'transaction_date' => '2019-05-17',
                'lines' => [
                    [
                        'description' => 'Nissan NV350 Urvan Ambulance — Engine No. YD25037887B',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 1788000.00,
                        'property_no' => '10706010-003-FIN574',
                        'date_acquired' => '2019-05-17',
                        'estimated_useful_life' => '15 years',
                    ],
                    [
                        'description' => 'Nissan NV350 Urvan Ambulance (Unit 2)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 1788000.00,
                        'property_no' => '10706010-003-FIN575',
                        'date_acquired' => '2019-05-17',
                        'estimated_useful_life' => '15 years',
                    ],
                ],
            ],

            // Nissan Ambulance — 10/26/2018
            [
                'employee' => 'Albert B. Lumapas',
                'transaction_date' => '2018-10-26',
                'lines' => [
                    [
                        'description' => 'Nissan Ambulance NV350 Urvan — Engine No. YD25032658B',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 2048000.00,
                        'property_no' => '107060107-001-FIC017',
                        'date_acquired' => '2018-10-26',
                        'estimated_useful_life' => '15 years',
                    ],
                ],
            ],

            // Suzuki APV — 5/11/2016 (no accountable person listed)
            [
                'employee' => 'Albert B. Lumapas',
                'transaction_date' => '2016-05-11',
                'lines' => [
                    [
                        'description' => 'Suzuki APV GA-MT Van — Engine No.: G16AID267961',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 1050000.00,
                        'property_no' => '241-003-UD-8733',
                        'date_acquired' => '2016-05-11',
                        'estimated_useful_life' => '15 years',
                    ],
                ],
            ],

            // ═══════════════════════════════════════
            // Rasphem R. Luib — 5/13/2025 (Radio equipment)
            // ═══════════════════════════════════════
            [
                'employee' => 'Rasphem R. Luib',
                'transaction_date' => '2025-05-13',
                'lines' => [
                    [
                        'description' => 'Digital Fixed Base Radio Augmentation — 2 units Motorola XiR M6660 VHF, Power Supply, Base Antenna Diamond BC100, RG8 Coaxial cable',
                        'quantity' => 1,
                        'unit' => 'lot',
                        'unit_cost' => 161280.00,
                        'property_no' => '10705010-001-VHF-DFBR-029',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                    ],
                    [
                        'description' => 'Digital Portable Radio — Motorola Mototrbo R7 VHF, Full Keypad, Digital Mode w/ GPS, 2.4" QVGA display (Unit 1)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 59640.00,
                        'property_no' => '10705070-001-VHF-DPRA-081',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                    ],
                    [
                        'description' => 'Digital Portable Radio — Motorola Mototrbo R7 VHF (Unit 2)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 59640.00,
                        'property_no' => '10705070-001-VHF-DPRA-082',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                    ],
                    [
                        'description' => 'Digital Portable Radio — Motorola Mototrbo R7 VHF (Unit 3)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 59640.00,
                        'property_no' => '10705070-001-VHF-DPRA-033',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                    ],
                    [
                        'description' => 'Digital Portable Radio — Motorola Mototrbo R7 VHF (Unit 4)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 59640.00,
                        'property_no' => '10705070-001-VHF-DPRA-034',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                    ],
                    [
                        'description' => 'Digital Portable Radio — Motorola Mototrbo R7 VHF (Unit 5)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 59640.00,
                        'property_no' => '10705070-001-VHF-DPRA-035',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                    ],
                    [
                        'description' => 'Digital Portable Radio — Motorola Mototrbo R7 VHF (Unit 6)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 59640.00,
                        'property_no' => '10705070-001-VHF-DPRA-036',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                    ],
                    [
                        'description' => 'Digital Portable Radio — Motorola Mototrbo R7 VHF (Unit 7)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 59640.00,
                        'property_no' => '10705070-001-VHF-DPRA-037',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                    ],
                    [
                        'description' => 'Digital Portable Radio — Motorola Mototrbo R7 VHF (Unit 8)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 59640.00,
                        'property_no' => '10705070-001-VHF-DPRA-038',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                    ],
                    [
                        'description' => 'Digital Portable Radio — Motorola Mototrbo R7 VHF (Unit 9)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 59640.00,
                        'property_no' => '10705070-001-VHF-DPRA-039',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                    ],
                    [
                        'description' => 'Digital Portable Radio — Motorola Mototrbo R7 VHF (Unit 10)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 59640.00,
                        'property_no' => '10705070-001-VHF-DPRA-040',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                    ],
                ],
            ],

            // ═══════════════════════════════════════
            // PDRRMO Operations Center — 5/13/2025 (Video Wall Augmentation — Cicero)
            // ═══════════════════════════════════════
            [
                'employee' => 'Cicero Cosme P. Tripoli',
                'transaction_date' => '2025-05-13',
                'reference_no' => 'VW-AUG-2025',
                'lines' => [
                    [
                        'description' => 'Video Wall Augmentation — 4 LED Monitors w/ wall mount, HDMI cable, AVR (EASY UPS BV 1000VA)',
                        'quantity' => 1,
                        'unit' => 'lot',
                        'unit_cost' => 1257516.74,
                        'property_no' => '10705070-003-VidDS-002',
                        'date_acquired' => '2025-05-13',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'Poor Condition / Unserviceable',
                    ],
                ],
            ],

            // ═══════════════════════════════════════
            // PDRRMO Operations Center — 07/01/2024 (Major IT procurement batch)
            // ═══════════════════════════════════════
            [
                'employee' => 'Cicero Cosme P. Tripoli',
                'transaction_date' => '2024-07-01',
                'lines' => [
                    [
                        'description' => 'IP Telephony Equipment with Voice Recording — Yeastar P560 (SN: 3632D3605273)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 489999.00,
                        'property_no' => '10705070-002-CoT-0003',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Desktop Terminal Set — Dell Optiplex 3000 SFF, i5, 8GB, 1TB, 21.5" monitor, 650VA UPS (SN: OC5GT7-QDC00-37E-07PL-A04)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 139800.00,
                        'property_no' => '10705070-999-DeTs-0001',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Desktop Terminal Set — Dell Optiplex 3000 SFF (SN: OC5GT7-QDC00-37E-0DSL-A04) Unit 2',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 139800.00,
                        'property_no' => '10705070-999-DeTs-0002',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Desktop Terminal Set — Dell Optiplex 3000 SFF Unit 3',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 139800.00,
                        'property_no' => '10705070-999-DeTs-0004',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Desktop Terminal Set — Dell Optiplex 3000 SFF Unit 4',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 139800.00,
                        'property_no' => '10705070-999-DeTs-0005',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Firewall Equipment — Netgate 6100 Base (SN: 2035233122)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 149998.00,
                        'property_no' => '10705070-999-FiE-0001',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => '24 Port Layer 3 Managed Switch — Huawei S5735-S24T4X (SN: K33A000105)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 150000.00,
                        'property_no' => '10705070-999-SWIT-002',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Incident Management System (Bayan 911) — DELL R350, Intel Xeon E-2334, 8GB, 2TB (SN: 6S0Y9V3)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 9899980.00,
                        'property_no' => null,
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Computer Aided Dispatch System — Dell R350 Server, Smart PTT Enterprises License, Motorola M6660 Control Station (SN: 5JDX1Z3)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 4899990.00,
                        'property_no' => '10705070-999-ComADS-001',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Network UPS — APC Smart UPS SMT3000RMI2UC 3000VA 230V (SN: SAS2326141292)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 250000.00,
                        'property_no' => '10705070-999-NetUPS-001',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Equipment Rack Cabinet',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 100000.00,
                        'property_no' => '10705070-999-EqRC-001',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '10 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Laptop Computer — Lenovo Slim 5i Ideapad, i5, 8GB, 14" w/ Windows OS (SN: MP2FLTQW)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 75000.00,
                        'property_no' => '10705070-999-Lap-002',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Structured Cabling & LAN Surveillance & Video Management System',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 300000.00,
                        'property_no' => '10705070-999-SC-LANS-VMS-001',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '10 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Videowall Display System — Dahua LS550-UCM-EF screens, Video Matrix M70-4U-E, HDMI cards (SN: 9J00421GAZ00001)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 4890000.00,
                        'property_no' => '10705070-003-ViDS-001',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'PTZ Camera 45x Optical Zoom — 2MP, 1/2.8" CMOS, Laser 550m, Auto-tracking, Face detection, 4K HDMI, 2TB NVR',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 74990.00,
                        'property_no' => null,
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Network Video Recorder — DHI-NVR4108HS-8P-4KS2/L, Seagate 2TB, Network Keyboard (SN: 9H0F38APAZ1A648)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 60000.00,
                        'property_no' => '10705070-999-NetVR-001',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Rackmount UPS — KEBOS GR11-3kVA (SN: 30002401040028)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 120000.00,
                        'property_no' => '10705070-999-RUPS-001',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'Poor Condition / Unserviceable',
                    ],
                    [
                        'description' => '55-inch LED Television Samsung 4K UHD (SN: 0NWM3NHW400717) Unit 1',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 64988.00,
                        'property_no' => '10705070-003-TV-0081',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'Poor Condition / Unserviceable',
                    ],
                    [
                        'description' => '55-inch LED Television Samsung 4K UHD Unit 2',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 64988.00,
                        'property_no' => '10705070-003-TV-0082',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'Poor Condition / Unserviceable',
                    ],
                    [
                        'description' => 'Bi-pod Pole Mount (10m) Digital Two-way Radio System',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 458999.00,
                        'property_no' => '10705070-999-OTW-RS-001',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Digital Two-way Radio Repeater System — Motorola SLR5300 VHF w/ Solar Power System, Battery, Antenna Diamond F23 (SN: 478IYN0762)',
                        'quantity' => 3,
                        'unit' => 'unit',
                        'unit_cost' => 1721990.00,
                        'property_no' => '10705070-001-VHF-0242',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '10 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Digital Fixed Base Radio VHF 45W Set w/ Backup Power — 136-174MHz, 256 channels, w/ Solar Station, Dispatch License, NTC License',
                        'quantity' => 21,
                        'unit' => 'unit',
                        'unit_cost' => 387997.00,
                        'property_no' => null,
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'Distributed to All LGUs of Surigao del Norte',
                    ],
                    [
                        'description' => 'Digital Fixed Base Radio Set — Motorola XiR-M8668i VHF',
                        'quantity' => 6,
                        'unit' => 'unit',
                        'unit_cost' => 189988.00,
                        'property_no' => '10705070-001-VHF-DFBR-026',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'PDRRMO, AFP, PNP, BFP, PCG, Governor\'s Boat',
                    ],
                    [
                        'description' => 'High Capacity LEO Satellite Connection with Managed WiFi — Starlink High Capacity w/ Router, Antenna, TP-Link EAP110, Captive Portal',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 789980.00,
                        'property_no' => '10705070-999-SatBIPC-001',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Standard Capacity LEO Satellite Connection with Managed WiFi — Starlink w/ Router, Antenna, Access Point, Captive Portal',
                        'quantity' => 21,
                        'unit' => 'unit',
                        'unit_cost' => 279990.00,
                        'property_no' => null,
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'Distributed to All LGUs of Surigao del Norte',
                    ],
                    [
                        'description' => 'Centralized Software-based Controller — Customized Landing Page, OMADA WiFi Voucher System, Emergency GSM Base Station',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 479999.00,
                        'property_no' => '10705070-999-CenSBC-001',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'GSM Base Transceiver Station — Ereach Emergency Base Station (SN: 022121PHADI0001)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 13467989.00,
                        'property_no' => '10705070-999-GSM-TS-001',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '10 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                    [
                        'description' => 'Telemetry Remote Terminal Unit (RTU) Set — Radar Water Level Sensor, Rain Gauge, IoT Gateway, 40W Solar Panel, 24AH battery',
                        'quantity' => 5,
                        'unit' => 'unit',
                        'unit_cost' => 1249989.00,
                        'property_no' => null,
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '10 years',
                        'remarks' => 'Installed in Sison & Surigao City',
                    ],
                    [
                        'description' => 'Telemetry Platform and Notification System — Bayan 911 Flood Monitoring, DELL R350, 8GB, 1TB (SN: HJDZ1Z3)',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 4899990.00,
                        'property_no' => '10705070-999-TelPNS-001',
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'No copy of PAR filed',
                    ],
                ],
            ],

            // PDRRMO Operations Center — Desktops (undated, PDRRMO Admin & ASSERT)
            [
                'employee' => 'Cicero Cosme P. Tripoli',
                'transaction_date' => '2024-07-01',
                'reference_no' => 'ADMIN-DT-2024',
                'lines' => [
                    [
                        'description' => 'Desktop Computer — PDRRMO Admin Division Unit 1',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 74700.00,
                        'property_no' => null,
                        'date_acquired' => null,
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'PDRRMO Admin Division',
                    ],
                    [
                        'description' => 'Desktop Computer — ASSERT Unit 2',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 74700.00,
                        'property_no' => null,
                        'date_acquired' => null,
                        'estimated_useful_life' => '5 years',
                        'remarks' => 'ASSERT',
                    ],
                    [
                        'description' => 'Conference Table — 12 Seater',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 150000.00,
                        'property_no' => null,
                        'date_acquired' => '2024-07-01',
                        'estimated_useful_life' => '10 years',
                    ],
                ],
            ],

            // Workstation cubicles (undated, no accountable person)
            [
                'employee' => 'Cicero Cosme P. Tripoli',
                'transaction_date' => '2024-07-01',
                'reference_no' => 'WS-CUB-2024',
                'lines' => [
                    [
                        'description' => '2-Staff Dynamic Office Workstation Cubicle with Desk — 283x283x110cm, Aluminum Alloy Partition w/ Clear Glass, E1-grade MFC, Filing cabinet',
                        'quantity' => 4,
                        'unit' => 'unit',
                        'unit_cost' => 70000.00,
                        'property_no' => null,
                        'date_acquired' => null,
                        'estimated_useful_life' => '10 years',
                    ],
                ],
            ],

            // ═══════════════════════════════════════
            // Edgar B. Catulay — Solar Panels
            // ═══════════════════════════════════════
            [
                'employee' => 'Edgar B. Catulay',
                'transaction_date' => '2024-11-20',
                'lines' => [
                    [
                        'description' => 'Solar Panel 30W — with cable assembly, stainless mechanical mounting (Tsunami Early Warning System - Alerting Station)',
                        'quantity' => 6,
                        'unit' => 'pcs',
                        'unit_cost' => 60000.00,
                        'property_no' => '10703050-004-SoPan-001',
                        'date_acquired' => '2024-11-20',
                        'estimated_useful_life' => '15 years',
                    ],
                ],
            ],

            // Edgar B. Catulay — 11/18/2024 (additional solar panel)
            [
                'employee' => 'Edgar B. Catulay',
                'transaction_date' => '2024-11-18',
                'lines' => [
                    [
                        'description' => 'Solar Panel 30W — with cable assembly, stainless mechanical mounting (Tsunami Early Warning System - Alerting Station)',
                        'quantity' => 1,
                        'unit' => 'pcs',
                        'unit_cost' => 60000.00,
                        'property_no' => '10703050-004-SoPan-002',
                        'date_acquired' => '2024-11-18',
                        'estimated_useful_life' => '15 years',
                    ],
                ],
            ],

            // ═══════════════════════════════════════
            // Rowelto E. Dumale Jr. — Aircon & Power Equipment
            // ═══════════════════════════════════════
            [
                'employee' => 'Rowelto E. Dumale Jr.',
                'transaction_date' => '2024-06-05',
                'lines' => [
                    [
                        'description' => '4.0HP (3TR) Floor Mounted Inverter Aircon — 37,000 BTU/H, 230V/60Hz, R410A, Indoor: 540x410x1825mm',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 143537.00,
                        'property_no' => '10705020-004-AFM-0026',
                        'date_acquired' => '2024-06-05',
                        'estimated_useful_life' => '10 years',
                    ],
                    [
                        'description' => '2.0HP Super Inverter Lunaire Series Aircon — 19,700 BTU/h, 230V/60Hz, R32, Indoor: 965x215x319mm',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 64555.00,
                        'property_no' => '10705020-004-AFM-0027',
                        'date_acquired' => '2024-06-05',
                        'estimated_useful_life' => '10 years',
                    ],
                ],
            ],

            // Rowelto E. Dumale Jr. — 12/13/2024 (Inverter & Battery)
            [
                'employee' => 'Rowelto E. Dumale Jr.',
                'transaction_date' => '2024-12-13',
                'lines' => [
                    [
                        'description' => '16kW Hybrid Inverter — 20800W Max PV Input, 290A Max Charging, 3 MPPT, 2 string per MPPT',
                        'quantity' => 1,
                        'unit' => 'pc',
                        'unit_cost' => 240000.00,
                        'property_no' => '10703050-002-INV-001',
                        'date_acquired' => '2024-12-13',
                        'estimated_useful_life' => '10 years',
                    ],
                    [
                        'description' => 'Lithium Battery 300AH 51.2V — 150A Charge, 7680W charge power, 200A discharge, 10240W discharge power',
                        'quantity' => 1,
                        'unit' => 'pc',
                        'unit_cost' => 224000.00,
                        'property_no' => '10703050-002-BAT-001',
                        'date_acquired' => '2024-12-13',
                        'estimated_useful_life' => '8 years',
                    ],
                ],
            ],

            // ═══════════════════════════════════════
            // Candice Danica A. Yuipco — 6/5/2024 (Aircon)
            // ═══════════════════════════════════════
            [
                'employee' => 'Candice Danica A. Yuipco',
                'transaction_date' => '2024-06-05',
                'lines' => [
                    [
                        'description' => '4.0HP (3TR) Floor Mounted Inverter Aircon — 37,000 BTU/H, 230V/60Hz, R410A',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 143537.00,
                        'property_no' => '10705020-004-AFM-0028',
                        'date_acquired' => '2024-06-05',
                        'estimated_useful_life' => '10 years',
                    ],
                    [
                        'description' => '2.5HP Super Inverter Lunaire Series Aircon — 25,600 BTU/h, 230V/60Hz, R32, Indoor: 1080x226x335mm',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 78486.00,
                        'property_no' => '10705020-004-AFM-0029',
                        'date_acquired' => '2024-06-05',
                        'estimated_useful_life' => '10 years',
                    ],
                ],
            ],

            // ═══════════════════════════════════════
            // Gilbert O. Calvadores — 6/5/2024 (Aircon)
            // ═══════════════════════════════════════
            [
                'employee' => 'Gilbert O. Calvadores',
                'transaction_date' => '2024-06-05',
                'lines' => [
                    [
                        'description' => '4.0HP (3TR) Floor Mounted Inverter Aircon — 37,000 BTU/H, 230V/60Hz, R410A',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unit_cost' => 143537.00,
                        'property_no' => '10705020-004-AFM-0030',
                        'date_acquired' => '2024-06-05',
                        'estimated_useful_life' => '10 years',
                    ],
                ],
            ],
        ];

        // ──────────────────────────────────────────────
        // 5. Create all issuances with auto-approve
        // ──────────────────────────────────────────────
        $created = 0;
        $lineCount = 0;

        DB::transaction(function () use ($issuances, $employees, $pdrrmo, $fc, $entityName, $createdBy, $itemsByName, &$created, &$lineCount) {
            foreach ($issuances as $data) {
                $employee = $employees[$data['employee']];
                $transactionDate = $data['transaction_date'];

                // Determine classification from first line cost
                $firstCost = (float) $data['lines'][0]['unit_cost'];
                $classification = $firstCost >= 50000 ? 'ppe' : ($firstCost >= 5000 ? 'sphv' : 'splv');
                $assetType = $classification === 'ppe' ? 'ppe' : 'semi_expendable';
                $docType = match ($classification) {
                    'ppe' => 'PAR',
                    'splv' => 'ICS-SPLV',
                    default => 'ICS-SPHV',
                };

                $tx = PropertyTransaction::create([
                    'entity_name'      => $entityName,
                    'office_id'        => $pdrrmo->id,
                    'employee_id'      => $employee->id,
                    'fund_cluster_id'  => $fc->id,
                    'transaction_date' => $transactionDate,
                    'reference_no'     => $data['reference_no'] ?? null,
                    'control_no'       => 'TMP',
                    'document_type'    => $docType,
                    'asset_type'       => $assetType,
                    'status'           => 'approved',
                    'created_by'       => $createdBy,
                    'submitted_at'     => $transactionDate,
                    'approved_at'      => $transactionDate,
                ]);

                $tx->update([
                    'control_no' => NumberGenerator::next($docType, $transactionDate),
                ]);

                // Create approval record
                $tx->approvals()->create([
                    'status'    => 'approved',
                    'acted_by'  => $createdBy,
                    'acted_at'  => $transactionDate,
                    'remarks'   => 'Auto-approved — PDRRMO inventory data import',
                ]);

                foreach ($data['lines'] as $line) {
                    $unitCost = (float) $line['unit_cost'];
                    $qty = (int) $line['quantity'];
                    $lineClassification = $unitCost >= 50000 ? 'ppe' : ($unitCost >= 5000 ? 'sphv' : 'splv');

                    $tx->lines()->create([
                        'item_id'               => $this->resolveItemId($line['description'], $itemsByName),
                        'quantity'              => $qty,
                        'unit'                  => $line['unit'],
                        'description'           => $line['description'],
                        'property_no'           => $line['property_no'] ?? null,
                        'date_acquired'         => $line['date_acquired'] ?? $transactionDate,
                        'unit_cost'             => $unitCost,
                        'total_cost'            => $qty * $unitCost,
                        'classification'        => $lineClassification,
                        'estimated_useful_life' => $line['estimated_useful_life'] ?? null,
                        'remarks'               => $line['remarks'] ?? null,
                        'item_status'           => 'active',
                    ]);

                    $lineCount++;
                }

                $created++;
            }
        });

        $this->command->info("Created {$created} issuances with {$lineCount} line items (all auto-approved).");
    }
}
