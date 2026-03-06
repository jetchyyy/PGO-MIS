<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // ═══════════════════════════════════════════════
            // MOTOR VEHICLES
            // ═══════════════════════════════════════════════
            [
                'name' => 'Customized 4000L Water Tanker',
                'description' => 'CAB AND CHASIS: G.V.W: 4,490kg, Max.Output (PS) ISO Net: 136; Max.Torque (Nm) ISO Net: 390; Emission Standard: Euro 4; Engine: Diesel Turbo-charged & Intercooled; 4 Cycle, Vertical, Inline, 4 Cylinder, OHV, Water-cooled; Fuel Injection: Electronic Control Common Rail Type; Displacement: 4.009L; Fuel Tank: 100L; Tires: 7.00R16-14PR',
                'unit' => 'unit',
                'unit_cost' => 9326400.00,
                'category' => 'Motor Vehicle',
            ],
            [
                'name' => 'Brand New Forklift',
                'description' => 'Industrial forklift for heavy lifting operations',
                'unit' => 'unit',
                'unit_cost' => 1750000.00,
                'category' => 'Machinery',
            ],
            [
                'name' => 'Sea Ambulance with Accessories',
                'description' => 'Overall Length: 8.80m; Beam: 2.64m; Depth: 1.19m; Draft: 0.45m; Engine: 150 HP (Twin); Capacity: 12 Passenger; Fuel Tank: 300L; Navigation: Coastal, Island to Island; Weight: 1600kg; Hull Color: White/Blue',
                'unit' => 'unit',
                'unit_cost' => 9990000.00,
                'category' => 'Motor Vehicle',
            ],
            [
                'name' => 'Customized Mass Evacuation Vehicle (HINO 300)',
                'description' => 'Brand New Customized Vehicle / Mass Evacuation Vehicle based on HINO 300 platform',
                'unit' => 'unit',
                'unit_cost' => 5499000.00,
                'category' => 'Motor Vehicle',
            ],
            [
                'name' => 'Rescue Truck with Crane, Rescue Tools and Equipment',
                'description' => 'Brand New Rescue Truck; Model: HINO XZU730LN; Includes crane, rescue tools and equipment',
                'unit' => 'unit',
                'unit_cost' => 9998500.00,
                'category' => 'Motor Vehicle',
            ],
            [
                'name' => 'Motorcycle Yamaha YTX125',
                'description' => 'Brand New Yamaha YTX125 motorcycle for field response operations',
                'unit' => 'unit',
                'unit_cost' => 86666.66,
                'category' => 'Motor Vehicle',
            ],
            [
                'name' => '4X4 M/T Rapid Response Rescue Vehicle',
                'description' => 'Brand New 4X4 Manual Transmission Rapid Response Rescue Vehicle',
                'unit' => 'unit',
                'unit_cost' => 2998000.00,
                'category' => 'Motor Vehicle',
            ],
            [
                'name' => 'Rescue Boat',
                'description' => 'Brand New Rescue Boat for water rescue operations',
                'unit' => 'unit',
                'unit_cost' => 1300000.00,
                'category' => 'Motor Vehicle',
            ],
            [
                'name' => 'Nissan NV350 Urvan Ambulance',
                'description' => 'Nissan NV350 Urvan configured as ambulance for emergency medical transport',
                'unit' => 'unit',
                'unit_cost' => 1788000.00,
                'category' => 'Motor Vehicle',
            ],
            [
                'name' => '6-Wheels 2500-L PTO Fire Truck',
                'description' => 'Brand New 6-wheels 2500-Liter PTO Fire Truck for firefighting operations',
                'unit' => 'unit',
                'unit_cost' => 4395000.00,
                'category' => 'Motor Vehicle',
            ],
            [
                'name' => 'Toyota HILUX Pick-up',
                'description' => 'Toyota HILUX Pick-up for field operations',
                'unit' => 'unit',
                'unit_cost' => 1723217.00,
                'category' => 'Motor Vehicle',
            ],
            [
                'name' => 'Toyota HIACE Commuter Ambulance',
                'description' => 'Toyota HIACE Commuter configured as ambulance for emergency medical transport',
                'unit' => 'unit',
                'unit_cost' => 1759556.00,
                'category' => 'Motor Vehicle',
            ],
            [
                'name' => 'Honda TMX125 Motorcycle',
                'description' => 'Honda TMX125 motorcycle for field response operations',
                'unit' => 'unit',
                'unit_cost' => 113800.00,
                'category' => 'Motor Vehicle',
            ],
            [
                'name' => 'Suzuki APV GA-MT Van',
                'description' => 'Suzuki APV GA Manual Transmission Van for personnel transport',
                'unit' => 'unit',
                'unit_cost' => 1050000.00,
                'category' => 'Motor Vehicle',
            ],
            [
                'name' => 'Honda XRM125 MSE Motorcycle',
                'description' => 'Honda XRM125 MSE motorcycle for field response operations',
                'unit' => 'unit',
                'unit_cost' => 64460.00,
                'category' => 'Motor Vehicle',
            ],
            [
                'name' => 'Honda ADV160 Response/Rescue Motorcycle',
                'description' => 'Customized Brand New Response/Rescue Motorcycle based on Honda ADV160 platform',
                'unit' => 'unit',
                'unit_cost' => 278500.00,
                'category' => 'Motor Vehicle',
            ],

            // ═══════════════════════════════════════════════
            // IT EQUIPMENT
            // ═══════════════════════════════════════════════
            [
                'name' => 'Lenovo ThinkPad P1 Gen 7 Laptop',
                'description' => 'High Capacity Laptop; P-cores up to 4.80 GHz; Windows 11 Home 64; 32GB LPDDR5X-7500MT/s (CAMM2); Includes ThinkPad Essential Plus 15.6-inch Backpack',
                'unit' => 'unit',
                'unit_cost' => 185850.45,
                'category' => 'IT Equipment',
            ],
            [
                'name' => 'MacBook Pro 14-inch M3 Laptop',
                'description' => 'M3 Chip Laptop; 12-core CPU, 18-core GPU; 18GB Unified Memory; 1TB SSD',
                'unit' => 'unit',
                'unit_cost' => 188788.20,
                'category' => 'IT Equipment',
            ],
            [
                'name' => 'iPad Pro 13-inch M4 Tablet',
                'description' => 'iOS Tablet Computer; iPad Pro 13-inch M4; 256GB storage; WiFi',
                'unit' => 'unit',
                'unit_cost' => 112088.20,
                'category' => 'IT Equipment',
            ],
            [
                'name' => 'Desktop Terminal Set (Dell Optiplex 3000 SFF)',
                'description' => 'Dell Optiplex 3000 SFF; Intel i5 processor; 8GB memory; 1TB storage; USB optical mouse; wired keyboard; noise cancelling headset; Windows 10 Pro; 650VA UPS; 21.5" LED monitor w/ stand',
                'unit' => 'unit',
                'unit_cost' => 139800.00,
                'category' => 'IT Equipment',
            ],
            [
                'name' => 'Firewall Equipment (Netgate 6100 Base)',
                'description' => 'Netgate 6100 Base firewall equipment with power cord and power supply',
                'unit' => 'unit',
                'unit_cost' => 149998.00,
                'category' => 'IT Equipment',
            ],
            [
                'name' => '24 Port Layer 3 Managed Switch',
                'description' => 'Huawei S5735-S24T4X; 24-port Layer 3 managed switch; includes power cords, RS-232 cable, SFP dust covers, mounting brackets',
                'unit' => 'unit',
                'unit_cost' => 150000.00,
                'category' => 'IT Equipment',
            ],
            [
                'name' => 'Incident Management System (Bayan 911)',
                'description' => 'Licensed Incident Management System installed in DELL R350; Intel Xeon E-2334; 4 cores/8 threads; 8GB memory; 2TB storage; includes mounting bracket, peripherals',
                'unit' => 'unit',
                'unit_cost' => 9899980.00,
                'category' => 'IT Equipment',
            ],
            [
                'name' => 'Computer Aided Dispatch System',
                'description' => 'Dell R350 Server; Intel Xeon E-2324; 8GB memory; 2TB storage; Smart PTT Enterprises License; Windows 11 Pro OEM; includes Control Station (Motorola M6660), antenna, power supply, coaxial cable, connectors',
                'unit' => 'unit',
                'unit_cost' => 4899990.00,
                'category' => 'IT Equipment',
            ],
            [
                'name' => 'Lenovo Slim 5i Ideapad Laptop',
                'description' => 'Laptop Computer; i5 Intel processor; 8GB RAM; 14" monitor; Windows OS',
                'unit' => 'unit',
                'unit_cost' => 75000.00,
                'category' => 'IT Equipment',
            ],
            [
                'name' => 'Structured Cabling & LAN Surveillance System',
                'description' => 'Structured Cabling & Local Area Network Surveillance & Video Management System; RJ45 cable box; Cable Manager (5 packs)',
                'unit' => 'unit',
                'unit_cost' => 300000.00,
                'category' => 'IT Equipment',
            ],
            [
                'name' => 'Videowall Display System (Dahua)',
                'description' => 'Dahua LS550-UCM-EF screens; Video Matrix Dahua M70-4U-E; 6CH HDMI Decoding Card; 4CH HDMI Encoding Card x2; L2 Industrial Switch DH-IS4207-4GT-120; includes installation and integration',
                'unit' => 'unit',
                'unit_cost' => 4890000.00,
                'category' => 'IT Equipment',
            ],
            [
                'name' => 'PTZ Camera 45x Optical Zoom',
                'description' => '2MP 45x Network PTZ Camera; 1/2.8" 2MP CMOS; Max 50/60fps@1080P; Laser distance up to 550m; Auto-tracking, Perimeter protection, Face detection; AI features; 4K HDMI output; min 2TB storage; includes Network Keyboard',
                'unit' => 'unit',
                'unit_cost' => 74990.00,
                'category' => 'IT Equipment',
            ],
            [
                'name' => 'Network Video Recorder',
                'description' => 'DHI-NVR4108HS-8P-4KS2/L; Seagate Skyhawk 2TB Surveillance Hard Drive; Network Keyboard DHI-NKB1000E',
                'unit' => 'unit',
                'unit_cost' => 60000.00,
                'category' => 'IT Equipment',
            ],
            [
                'name' => 'Desktop Computer',
                'description' => 'Desktop computer for office use',
                'unit' => 'unit',
                'unit_cost' => 74700.00,
                'category' => 'IT Equipment',
            ],
            [
                'name' => 'Centralized Software-based Controller',
                'description' => 'Customized Landing Page; Wi-Fi Voucher System (OMADA WiFi Voucher System); Emergency GSM Base Station',
                'unit' => 'unit',
                'unit_cost' => 479999.00,
                'category' => 'IT Equipment',
            ],
            [
                'name' => 'Telemetry Platform and Notification System',
                'description' => 'Bayan 911 Flood Monitoring System; Web-based remote monitoring of Telemetry RTU; DELL R350 Intel Xeon Processor; 8GB RAM; 1TB HDD',
                'unit' => 'unit',
                'unit_cost' => 4899990.00,
                'category' => 'IT Equipment',
            ],

            // ═══════════════════════════════════════════════
            // COMMUNICATION EQUIPMENT
            // ═══════════════════════════════════════════════
            [
                'name' => 'Video Conference System (Logitech Rally Plus)',
                'description' => 'Logitech Rally Plus PTZ Camera; up to 4K UHD resolution; 13MP Image Sensors; 15x HD Zoom; 2 Microphones included',
                'unit' => 'lot',
                'unit_cost' => 347077.50,
                'category' => 'Communication Equipment',
            ],
            [
                'name' => 'Digital Fixed Base Radio Augmentation (Motorola XiR M6660)',
                'description' => '2 units Motorola XiR M6660 VHF Radio; includes Base Radio unit, Microphone, Power cable, Mount; 2 pcs Power Supply Unit; 2 pcs Base Antenna Diamond BC100; 2 lot RG8 Coaxial cable with Connectors',
                'unit' => 'lot',
                'unit_cost' => 161280.00,
                'category' => 'Communication Equipment',
            ],
            [
                'name' => 'Digital Portable Radio (Motorola Mototrbo R7 VHF)',
                'description' => 'Motorola Mototrbo R7 VHF; includes Unit, Battery, Antenna, Charger, Power Supply, Belt Clip; Subscriber License for Dispatch; 1 year NTC license; Full Keypad and Digital Mode with GPS; 2.4" QVGA display',
                'unit' => 'unit',
                'unit_cost' => 59640.00,
                'category' => 'Communication Equipment',
            ],
            [
                'name' => 'Video Wall Augmentation (LED Monitors)',
                'description' => '4 LED Monitors with wall mount (Front Maintenance Bracket LS550-WS); HDMI cable; AVR (EASY UPS BV 1000VA); compatible with existing video matrix system; includes installation, integration, restoration',
                'unit' => 'lot',
                'unit_cost' => 1257516.74,
                'category' => 'Communication Equipment',
            ],
            [
                'name' => 'LEO Satellite Internet (Standard Plan)',
                'description' => 'Low Earth Orbit Satellite Internet Access; Standard service plan; one year technical support; includes ethernet adapter',
                'unit' => 'lot',
                'unit_cost' => 175980.00,
                'category' => 'Communication Equipment',
            ],
            [
                'name' => 'IP Telephony Equipment with Voice Recording',
                'description' => 'Yeastar P560; LAN cable; Power cord; Mounting Bracket; Front cover',
                'unit' => 'unit',
                'unit_cost' => 489999.00,
                'category' => 'Communication Equipment',
            ],
            [
                'name' => 'Bi-pod Pole Mount (10m) Digital Two-way Radio',
                'description' => 'Bi-pod Pole mount at least 10m height for Digital Two-way Radio System',
                'unit' => 'unit',
                'unit_cost' => 458999.00,
                'category' => 'Communication Equipment',
            ],
            [
                'name' => 'Digital Two-way Radio Repeater System',
                'description' => 'VHF Repeater Motorola SLR5300; Back-up Power Supply; 150AH Battery; Repeater Antenna Diamond F23 x3; Belden 9913 Coaxial Cable (60m) x3; Connectors & Surge suppressor; Solar Power System (370W panel, charge controller, 100AH deep cycle battery x3)',
                'unit' => 'unit',
                'unit_cost' => 1721990.00,
                'category' => 'Communication Equipment',
            ],
            [
                'name' => 'Digital Fixed Base Radio VHF 45W Set w/ Backup Power',
                'description' => 'VHF 136-174MHz; 25-45W output; 256 channels; 12.5/25KHz spacing; 4FSK Digital Modulation; includes 15A Power Supply w/ Battery Charger; Base Radio Antenna; Portable Solar Station (400W inverter, 60W foldable solar panel); Dispatch Client License; 1yr NTC License',
                'unit' => 'unit',
                'unit_cost' => 387997.00,
                'category' => 'Communication Equipment',
            ],
            [
                'name' => 'Digital Fixed Base Radio Set (Motorola XiR-M8668i VHF)',
                'description' => 'Motorola XiR-M8668i VHF Digital Fixed Base Radio Set',
                'unit' => 'unit',
                'unit_cost' => 189988.00,
                'category' => 'Communication Equipment',
            ],
            [
                'name' => 'High Capacity LEO Satellite Connection with Managed WiFi',
                'description' => 'Starlink High Capacity LEO VSAT Terminal Kit w/ Router; VSAT antenna; Outdoor Access Point TP-Link EAP110 with POE; Starlink Ethernet Adapter; Captive Portal and Guest Management System',
                'unit' => 'unit',
                'unit_cost' => 789980.00,
                'category' => 'Communication Equipment',
            ],
            [
                'name' => 'Standard Capacity LEO Satellite Connection with Managed WiFi',
                'description' => 'Starlink Standard Capacity LEO VSAT Terminal Kit w/ Router; VSAT antenna; Outdoor Access Point with POE; Ethernet Adapter; Captive Portal and Guest Management System',
                'unit' => 'unit',
                'unit_cost' => 279990.00,
                'category' => 'Communication Equipment',
            ],
            [
                'name' => 'GSM Base Transceiver Station',
                'description' => 'Ereach Emergency GSM Base Transceiver Station for emergency communications',
                'unit' => 'unit',
                'unit_cost' => 13467989.00,
                'category' => 'Communication Equipment',
            ],
            [
                'name' => '55-inch LED Television (Samsung 4K UHD)',
                'description' => 'Samsung 55" 4K UHD TV for operations center display',
                'unit' => 'unit',
                'unit_cost' => 64988.00,
                'category' => 'Communication Equipment',
            ],

            // ═══════════════════════════════════════════════
            // TECHNICAL & SCIENTIFIC EQUIPMENT
            // ═══════════════════════════════════════════════
            [
                'name' => 'Telemetry Remote Terminal Unit (RTU) Set',
                'description' => 'Radar Water Level Sensor; Tipping Bucket Rain Gauge; Telemetry Data Collection System (IoT Gateway); Solar Panel 40W with charge controller and 24AH battery; Customized mounting bracket with outdoor equipment enclosure',
                'unit' => 'unit',
                'unit_cost' => 1249989.00,
                'category' => 'Technical & Scientific Equipment',
            ],
            [
                'name' => 'Solar Panel 30W (Tsunami Early Warning)',
                'description' => '30 watts solar panel with cable assembly including stainless mechanical mounting; part of Tsunami Early Warning System Alerting Station',
                'unit' => 'pcs',
                'unit_cost' => 60000.00,
                'category' => 'Technical & Scientific Equipment',
            ],

            // ═══════════════════════════════════════════════
            // FURNITURE & FIXTURES
            // ═══════════════════════════════════════════════
            [
                'name' => 'Office Furniture (Workstation-Fishbone 4-Seater)',
                'description' => 'Workstation-Fishbone type, full fabric partition, 4 seater H120xW120xD60cm; Office Executive Table W120xD60xH75cm; Midback chair with armrest; Conference table W120xD60xH75cm',
                'unit' => 'lot',
                'unit_cost' => 319570.00,
                'category' => 'Furniture & Fixtures',
            ],
            [
                'name' => 'Conference Table (12-Seater)',
                'description' => '12-seater conference table for meeting room',
                'unit' => 'unit',
                'unit_cost' => 150000.00,
                'category' => 'Furniture & Fixtures',
            ],
            [
                'name' => '2-Staff Dynamic Office Workstation Cubicle with Desk',
                'description' => 'Overall Dimensions: 283x283x110cm; Partition: 30mm thick 6063 Aluminum Alloy with Clear Glass; Table Height: 75cm; E1-grade MFC board with White finish; 2 circular grommet holes; Filing cabinet included',
                'unit' => 'unit',
                'unit_cost' => 70000.00,
                'category' => 'Furniture & Fixtures',
            ],

            // ═══════════════════════════════════════════════
            // MACHINERY / POWER EQUIPMENT
            // ═══════════════════════════════════════════════
            [
                'name' => '100kVA Generator Set',
                'description' => '100kVA Generator Set for emergency power supply',
                'unit' => 'unit',
                'unit_cost' => 1800000.00,
                'category' => 'Machinery',
            ],
            [
                'name' => '50kVA Generator Set',
                'description' => '50kVA Generator Set for emergency power supply',
                'unit' => 'unit',
                'unit_cost' => 1480000.00,
                'category' => 'Machinery',
            ],
            [
                'name' => 'Chainsaw',
                'description' => 'Chainsaw for disaster response clearing operations',
                'unit' => 'pc',
                'unit_cost' => 53000.00,
                'category' => 'Machinery',
            ],
            [
                'name' => '16kW Hybrid Inverter',
                'description' => '16kW Hybrid Inverter; 20800W Max PV Input; 290A Max Charging and Discharging; Self Adaptation to BMS; 125V-500V PV Input; 3 MPPT; 2 string per MPPT; Max AC Output Power',
                'unit' => 'pc',
                'unit_cost' => 240000.00,
                'category' => 'Machinery',
            ],
            [
                'name' => 'Lithium Battery 300AH 51.2V',
                'description' => 'Lithium Battery 300AH 51.2V; 150A Charge Current; 7680W charge power; 200A discharge current; 10240W discharge power; 540A short circuit current',
                'unit' => 'pc',
                'unit_cost' => 224000.00,
                'category' => 'Machinery',
            ],

            // ═══════════════════════════════════════════════
            // OTHER EQUIPMENT
            // ═══════════════════════════════════════════════
            [
                'name' => 'Network UPS (APC Smart UPS 3000VA)',
                'description' => 'APC Smart UPS SMT3000RMI2UC; 3000VA Network UPS; 230V; Line-Interactive',
                'unit' => 'unit',
                'unit_cost' => 250000.00,
                'category' => 'Other Equipment',
            ],
            [
                'name' => 'Equipment Rack Cabinet',
                'description' => 'Equipment Rack Cabinet for server and network equipment housing',
                'unit' => 'unit',
                'unit_cost' => 100000.00,
                'category' => 'Other Equipment',
            ],
            [
                'name' => 'Rackmount UPS (KEBOS GR11-3kVA)',
                'description' => 'KEBOS GR11-3kVA Rackmount Uninterruptible Power Supply',
                'unit' => 'unit',
                'unit_cost' => 120000.00,
                'category' => 'Other Equipment',
            ],
            [
                'name' => '4.0HP (3TR) Floor Mounted Inverter Aircon',
                'description' => 'Cooling Capacity: 37,000 BTU/H; Power: 230V/60Hz/1Ph; Rated Power: 3,600W; EER: 10.84 kJ/W-Hr; Refrigerant: R410A; Indoor: 540x410x1825mm; Outdoor: 946x420x810mm',
                'unit' => 'unit',
                'unit_cost' => 143537.00,
                'category' => 'Other Equipment',
            ],
            [
                'name' => '2.5HP Super Inverter Lunaire Series Aircon',
                'description' => 'Cooling Capacity: 25,600 BTU/h; 2.5HP; Power: 230V/60Hz/1Ph; Rated Power: 1950W; EER: 13.1; Refrigerant: R32; Indoor: 1080x226x335mm; Outdoor: 845x363x702mm',
                'unit' => 'unit',
                'unit_cost' => 78486.00,
                'category' => 'Other Equipment',
            ],
            [
                'name' => '2.0HP Super Inverter Lunaire Series Aircon',
                'description' => 'Cooling Capacity: 19,700 BTU/h; 2.0HP; Power: 230V/60Hz/1Ph; Rated Power: 1500W; EER: 13.1; Refrigerant: R32; Indoor: 965x215x319mm; Outdoor: 800x333x554mm',
                'unit' => 'unit',
                'unit_cost' => 64555.00,
                'category' => 'Other Equipment',
            ],
            [
                'name' => '2-Door Top Mount Freezer Refrigerator',
                'description' => '8.0 cu. ft.; 225L gross / 209L net; 555x1520x585mm; Inverter Technology; DoorCooling+; 46kg; 230V/60Hz; Refrigerant: R600a',
                'unit' => 'unit',
                'unit_cost' => 50000.00, // Estimated — no value on original inventory
                'category' => 'Other Equipment',
                'estimated_useful_life' => '10 years',
            ],
        ];

        foreach ($items as $data) {
            $data['classification'] = Item::classifyByCost((float) $data['unit_cost']);
            $data['is_active'] = true;

            Item::firstOrCreate(
                ['name' => $data['name']],
                $data
            );
        }

        $this->command->info('Seeded ' . count($items) . ' items into the catalog.');
    }
}
