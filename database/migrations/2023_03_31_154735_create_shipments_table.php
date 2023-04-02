<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('type');
            $table->string('shipment_type');
            $table->timestamp('delivery_date')->nullable();
            $table->string('shipment_value')->nullable();
            $table->string('shipment_weight');
            $table->string('shipment_temperature')->nullable();
            $table->string('shipment_consignee_name');
            $table->string('shipment_from_lat')->nullable();
            $table->string('shipment_from_long')->nullable();
            $table->string('shipment_to_lat')->nullable();
            $table->string('shipment_to_long')->nullable();
            $table->string('shipment_consignee_city')->nullable();
            $table->string('shipment_consignee_address')->nullable();
            $table->string('shipment_consignee_address_short')->nullable();
            $table->string('shipment_consignee_address_url')->nullable();
            $table->string('shipment_port')->nullable();
            $table->string('shipment_bill_of_lading')->nullable();
            $table->string('shipment_bill_of_lading_file')->nullable();
            $table->string('shipment_annulment_number')->nullable();
            $table->string('shipment_annulment_file')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('payment_method')->default(0);
            $table->string('shipment_number');
            $table->string('total');
            $table->string('vat')->nullable();
            $table->string('customs_declaration_fees')->nullable();
            $table->string('delivery_authorization')->nullable();
            $table->string('customs_clearance')->nullable();
            $table->string('shipment_delivery')->nullable();
            $table->string('translate')->nullable();
            $table->string('workers_wages')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
