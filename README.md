# Requirements
- Composer
- PHP >= v7.3
- Laravel v8.x
- MySQL
- Read full documentation on how to set up laravel project here https://laravel.com/docs/8.x

# Set Up
- Create database
- Create new file with name `.env` based on `.env.example` to `.env`
- Update database credentials on `.env`
- Run db migration `php artisan migrate`
- Run the server `php artisan serve`

# Available Routes

## GET
- `jenis-barang`
- `jenis-barang/{id}`
- `barang`
- `barang/{id}`
- `transaksi`
- `transaksi/{id}`

## POST
### Route :  `jenis-barang`
### Request :
- `nama: string`
------------------------------
### Route : `barang`
### Request :
- `nama: string`
- `id_jenis_barang: integer`
- `stock: integer`
------------------------------
### Route : `transaksi`
### Request :
- `id_barang: integer`
- `jumlah: integer`
------------------------------

## PUT
### Route :  `jenis-barang/{id}`
### Request :
- `nama: string`
------------------------------
### Route : `barang/{id}`
### Request :
- `nama: string`
- `id_jenis_barang: integer`
- `stock: integer`
------------------------------
### Route : `transaksi/{id}`
### Request :
- `id_barang: integer`
- `jumlah: integer`
------------------------------

## DELETE
- `jenis-barang/{id}`
- `barang/{id}`
- `transaksi/{id}`
