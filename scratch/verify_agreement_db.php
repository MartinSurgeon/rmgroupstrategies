<?php
require_once __DIR__ . '/../includes/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $db = new PDO($dsn, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $initial_count = $db->query("SELECT COUNT(*) FROM rental_agreements")->fetchColumn();
    echo "Initial rental_agreements count in DB: " . $initial_count . "\n";

    // Simulate complete POST submission
    $agreement_id = 'RM-AGR-' . date('Y') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
    $stmt = $db->prepare("INSERT INTO rental_agreements (
        agreement_id, rental_type, legal_name, business_name, billing_address, city_state_zip,
        mobile_phone, alternate_phone, email, driver_license_no, driver_license_state, driver_license_exp,
        date_of_birth, authorized_rep, emergency_contact_name, emergency_contact_phone,
        fulfillment_type, jobsite_address, jobsite_city_state_zip, project_type, authorized_operators,
        equipment_schedule, rental_start_date, rental_start_time, rental_return_date, rental_return_time,
        rental_duration_days, estimated_rental_charge, security_deposit, delivery_pickup_fee,
        damage_waiver_status, damage_waiver_fee, taxes_amount, estimated_total,
        payment_method, cardholder_name, card_last_four, card_exp,
        insurance_carrier, insurance_policy_no, insurance_exp, coi_status,
        acknowledgments_accepted, terms_accepted, signature_type, signature_data,
        signer_printed_name, signer_ip, status
    ) VALUES (
        :agreement_id, :rental_type, :legal_name, :business_name, :billing_address, :city_state_zip,
        :mobile_phone, :alternate_phone, :email, :driver_license_no, :driver_license_state, :driver_license_exp,
        :date_of_birth, :authorized_rep, :emergency_contact_name, :emergency_contact_phone,
        :fulfillment_type, :jobsite_address, :jobsite_city_state_zip, :project_type, :authorized_operators,
        :equipment_schedule, :rental_start_date, :rental_start_time, :rental_return_date, :rental_return_time,
        :rental_duration_days, :estimated_rental_charge, :security_deposit, :delivery_pickup_fee,
        :damage_waiver_status, :damage_waiver_fee, :taxes_amount, :estimated_total,
        :payment_method, :cardholder_name, :card_last_four, :card_exp,
        :insurance_carrier, :insurance_policy_no, :insurance_exp, :coi_status,
        1, 1, :signature_type, :signature_data,
        :signer_printed_name, :signer_ip, 'pending'
    )");

    $stmt->execute([
        ':agreement_id'             => $agreement_id,
        ':rental_type'               => 'individual',
        ':legal_name'                => 'Erica Rivera RM Verification',
        ':business_name'             => 'RM Nevada Series LLC',
        ':billing_address'           => '789 Commercial Blvd',
        ':city_state_zip'            => 'Las Vegas, NV 89109',
        ':mobile_phone'              => '702-504-8128',
        ':alternate_phone'           => '',
        ':email'                     => 'info@rmgroupstrategies.com',
        ':driver_license_no'         => 'NV987654321',
        ':driver_license_state'      => 'NV',
        ':driver_license_exp'        => '08/30',
        ':date_of_birth'             => '1985-05-15',
        ':authorized_rep'            => 'Erica Rivera',
        ':emergency_contact_name'    => 'RM Support',
        ':emergency_contact_phone'   => '702-504-8128',
        ':fulfillment_type'          => 'delivery',
        ':jobsite_address'           => '1000 Jobsite Way',
        ':jobsite_city_state_zip'    => 'Henderson, NV 89015',
        ':project_type'              => 'Commercial Concrete Demolition & Dust Clearance',
        ':authorized_operators'      => 'Lead Contractor',
        ':equipment_schedule'        => json_encode([
            [
                'qty' => 1,
                'tool' => 'Bauer Heavy-Duty Demolition Jackhammer',
                'serial' => 'BAU-DH15-NV',
                'item_charge' => 200.00
            ],
            [
                'qty' => 1,
                'tool' => 'RedMax EBZ8560 Commercial Backpack Blower',
                'serial' => 'RMX-EBZ8560-NV',
                'item_charge' => 200.00
            ]
        ]),
        ':rental_start_date'         => '2026-09-01',
        ':rental_start_time'         => '08:00 AM',
        ':rental_return_date'        => '2026-09-03',
        ':rental_return_time'        => '05:00 PM',
        ':rental_duration_days'      => 2,
        ':estimated_rental_charge'   => 400.00,
        ':security_deposit'          => 100.00,
        ':delivery_pickup_fee'       => 0.00,
        ':damage_waiver_status'      => 'declined',
        ':damage_waiver_fee'         => 0.00,
        ':taxes_amount'              => 0.00,
        ':estimated_total'           => 500.00,
        ':payment_method'            => 'credit_card',
        ':cardholder_name'           => 'Erica Rivera',
        ':card_last_four'            => '5678',
        ':card_exp'                  => '09/29',
        ':insurance_carrier'         => '',
        ':insurance_policy_no'       => '',
        ':insurance_exp'             => '',
        ':coi_status'                => 'not_required',
        ':signature_type'            => 'drawn',
        ':signature_data'            => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
        ':signer_printed_name'       => 'Erica Rivera',
        ':signer_ip'                 => '127.0.0.1'
    ]);

    $new_id = $db->lastInsertId();
    echo "SUCCESS: Saved agreement to DB with ID: " . $new_id . " and Agreement ID: " . $agreement_id . "\n";

    $final_count = $db->query("SELECT COUNT(*) FROM rental_agreements")->fetchColumn();
    echo "Updated rental_agreements count in DB: " . $final_count . "\n";

    // Fetch the inserted record to verify all columns
    $row = $db->query("SELECT * FROM rental_agreements WHERE id = " . $new_id)->fetch(PDO::FETCH_ASSOC);
    echo "Verification - Legal Name: " . $row['legal_name'] . " | Total: $" . $row['estimated_total'] . " | Status: " . $row['status'] . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
