<?php

namespace Illuminate\Tests\Integration\Database\EloquentBelongsToManyTest;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Tests\Integration\Database\DatabaseTestCase;

/**
 * @group integration
 */
class QueryBuilderTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('content');
            $table->timestamp('created_at');
        });

        DB::table('posts')->insert([
            ['title' => 'Foo Post', 'content' => 'Lorem Ipsum.', 'created_at' => new Carbon('2017-11-12 13:14:15')],
            ['title' => 'Bar Post', 'content' => 'Lorem Ipsum.', 'created_at' => new Carbon('2018-01-02 03:04:05')],
        ]);
    }

    public function testWhereDate()
    {
        $this->assertSame(1, DB::table('posts')->whereDate('created_at', '2018-01-02')->count());
        $this->assertSame(1, DB::table('posts')->whereDate('created_at', new Carbon('2018-01-02'))->count());
    }

    public function testOrWhereDate()
    {
        $this->assertSame(2, DB::table('posts')->where('id', 1)->orWhereDate('created_at', '2018-01-02')->count());
        $this->assertSame(2, DB::table('posts')->where('id', 1)->orWhereDate('created_at', new Carbon('2018-01-02'))->count());
    }

    public function testWhereDay()
    {
        $this->assertSame(1, DB::table('posts')->whereDay('created_at', '02')->count());
        $this->assertSame(1, DB::table('posts')->whereDay('created_at', 2)->count());
        $this->assertSame(1, DB::table('posts')->whereDay('created_at', new Carbon('2018-01-02'))->count());
    }

    public function testOrWhereDay()
    {
        $this->assertSame(2, DB::table('posts')->where('id', 1)->orWhereDay('created_at', '02')->count());
        $this->assertSame(2, DB::table('posts')->where('id', 1)->orWhereDay('created_at', 2)->count());
        $this->assertSame(2, DB::table('posts')->where('id', 1)->orWhereDay('created_at', new Carbon('2018-01-02'))->count());
    }

    public function testWhereMonth()
    {
        $this->assertSame(1, DB::table('posts')->whereMonth('created_at', '01')->count());
        $this->assertSame(1, DB::table('posts')->whereMonth('created_at', 1)->count());
        $this->assertSame(1, DB::table('posts')->whereMonth('created_at', new Carbon('2018-01-02'))->count());
    }

    public function testOrWhereMonth()
    {
        $this->assertSame(2, DB::table('posts')->where('id', 1)->orWhereMonth('created_at', '01')->count());
        $this->assertSame(2, DB::table('posts')->where('id', 1)->orWhereMonth('created_at', 1)->count());
        $this->assertSame(2, DB::table('posts')->where('id', 1)->orWhereMonth('created_at', new Carbon('2018-01-02'))->count());
    }

    public function testWhereYear()
    {
        $this->assertSame(1, DB::table('posts')->whereYear('created_at', '2018')->count());
        $this->assertSame(1, DB::table('posts')->whereYear('created_at', 2018)->count());
        $this->assertSame(1, DB::table('posts')->whereYear('created_at', new Carbon('2018-01-02'))->count());
    }

    public function testOrWhereYear()
    {
        $this->assertSame(2, DB::table('posts')->where('id', 1)->orWhereYear('created_at', '2018')->count());
        $this->assertSame(2, DB::table('posts')->where('id', 1)->orWhereYear('created_at', 2018)->count());
        $this->assertSame(2, DB::table('posts')->where('id', 1)->orWhereYear('created_at', new Carbon('2018-01-02'))->count());
    }

    public function testWhereTime()
    {
        $this->assertSame(1, DB::table('posts')->whereTime('created_at', '03:04:05')->count());
        $this->assertSame(1, DB::table('posts')->whereTime('created_at', new Carbon('2018-01-02 03:04:05'))->count());
    }

    public function testOrWhereTime()
    {
        $this->assertSame(2, DB::table('posts')->where('id', 1)->orWhereTime('created_at', '03:04:05')->count());
        $this->assertSame(2, DB::table('posts')->where('id', 1)->orWhereTime('created_at', new Carbon('2018-01-02 03:04:05'))->count());
    }

    public function testPaginateWithSpecificColumns()
    {
        $result = DB::table('posts')->paginate(5, ['title', 'content']);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals($result->items(), [
            (object) ['title' => 'Foo Post', 'content' => 'Lorem Ipsum.'],
            (object) ['title' => 'Bar Post', 'content' => 'Lorem Ipsum.'],
        ]);
    }
}
