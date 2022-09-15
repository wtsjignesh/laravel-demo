<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /**
    # Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different locations

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('role');
            $table->timestamps();
        });

        Schema::create('cinema_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('area');
            $table->string('city');
            $table->string('state');
            $table->integer('zip');
            $table->integer('total_seats');
            $table->integer('seats_per_row');
            $table->timestamps();
        });

        Schema::create('seating_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('VIP, Premuim, Gold');
            $table->integer('cinema_location_id')->unsigned();
            $table->foreign('cinema_location_id')->references('id')->on('cinema_locations')->onDelete('cascade');
            $table->integer('no_of_seats');
            $table->float('premium_percentage', 8, 2);
            $table->timestamps();
        });

        Schema::create('movies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('duration');
            $table->tinyInteger('status')->default(1)->comment('1 - Publish, 0 - Unpublish');
            $table->timestamps();
        });

        Schema::create('movie_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('movie_id')->unsigned();
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->integer('cinema_location_id')->unsigned();
            $table->foreign('cinema_location_id')->references('id')->on('cinema_locations')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });

        Schema::create('movie_shows', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('movie_slot_id')->unsigned();
            $table->foreign('movie_slot_id')->references('id')->on('movie_slots')->onDelete('cascade');
            $table->integer('available_seats');
            $table->time('start_time');
            $table->tinyInteger('status')->default(1)->comment('1 - Publish, 0 - Unpublish');
            $table->timestamps();
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('movie_show_id')->unsigned();
            $table->foreign('movie_show_id')->references('id')->on('movie_shows')->onDelete('cascade');
            $table->float('total_amount', 8, 2);
            $table->tinyInteger('status')->comment('1 - Paid, 0 - Pending');
            $table->timestamps();
        });

        Schema::create('reservation_seats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reservation_id')->unsigned();
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            $table->integer('seating_category_id')->unsigned();
            $table->foreign('seating_category_id')->references('id')->on('seating_categories')->onDelete('cascade');
            $table->string('selected_column');
            $table->integer('seat_no');
            $table->float('amount', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('cinema_locations');
        Schema::dropIfExists('seating_categories');
        Schema::dropIfExists('movies');
        Schema::dropIfExists('movie_slots');
        Schema::dropIfExists('movie_shows');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('reservation_seats');
    }
}
