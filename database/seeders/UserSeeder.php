<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
User::create(array (
    'id' => 1,
    'name' => 'Sitaram N Kawade',
    'email' => 'sitaram.kawade@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$Q8IW4IJ47Y9hoHbEtT/Zt.Ob4//9JZxASSDnyxvUaAaW/nUVxM.9C',
    'user_contact_no' => '',
    'remember_token' => 'Dy9hCLBGUMPqxBkFj71JT7vRijTXRYw9IWptGuvg4fJMNQJfDB8FREmVxHIC',
    'department_id' => 32,
    'role_id' => 1,
    'college_id' => 1,
    'is_active' => 1,
    'deleted_at' => NULL,
    'created_at' => '2021-06-01T20:45:00.000000Z',
    'updated_at' => '2022-10-28T16:37:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 3,
    'name' => 'Vijay Raut',
    'email' => 'rautdigvijay88@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$5WXlKY1nA8fCnnuM1yrRxeGNnc9PK3k1C/He0hoNA5/eS.bK1QWzy',
    'user_contact_no' => '',
    'remember_token' => 'ls5goY4RID0knvd2sfrU0jBjbc3tIL5Uh3mEVOmagmimwbVWvyoyKOcouJBr',
    'department_id' => 32,
    'role_id' => 1,
    'college_id' => 1,
    'is_active' => 1,
    'deleted_at' => NULL,
    'created_at' => '2021-06-01T20:45:00.000000Z',
    'updated_at' => '2022-11-14T08:49:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 4,
    'name' => 'Sandhya Tamhane',
    'email' => 'bhaktitamhane81@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$WL5V5.1zqfF5yiMZ6LqEI.ud8TKyjS60LpErr8pr5n6.mEgznWKxS',
    'user_contact_no' => '',
    'remember_token' => 'P55k2nN6KOiQNIp7q37K9WoWK7qPxTpYFNaqI3dPVKDwM3sCMGmwQtLryTb8',
    'department_id' => 32,
    'role_id' => 2,
    'college_id' => 1,
    'is_active' => 1,
    'deleted_at' => NULL,
    'created_at' => '2021-06-01T20:45:00.000000Z',
    'updated_at' => '2023-09-19T23:01:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 5,
    'name' => 'WALE DADASAHEB',
    'email' => 'dadasahebwale17293@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$8X.4SPiCctT9Oz6Ww5iz9Od.zr31G0zsmCFmV2aGainY94lQgIliu',
    'user_contact_no' => '',
    'remember_token' => 'xsHCoEdKgNZtDyxM7gOfDndkXW2t1pwR5m6QYhsuFlbcEd0OXhPqB0WWw380',
    'department_id' => 32,
    'role_id' => 11,
    'college_id' => 1,
    'is_active' => 1,
    'deleted_at' => NULL,
    'created_at' => '2021-12-01T04:17:00.000000Z',
    'updated_at' => '2023-08-29T23:00:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 6,
    'name' => 'JEJURKAR RANJEET',
    'email' => 'ranjeetjejurkar1996@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$OvfjH5NxqljzXHnKay.zQOh5CvhJ49tDx18s5xkQOF9nlnxnp3LMK',
    'user_contact_no' => '',
    'remember_token' => NULL,
    'department_id' => 32,
    'role_id' => 2,
    'college_id' => 1,
    'is_active' => 1,
    'deleted_at' => NULL,
    'created_at' => '2021-12-10T05:51:00.000000Z',
    'updated_at' => '2023-08-02T05:43:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 9,
    'name' => 'SONAWANE D.A.',
    'email' => 'dasonawane24@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$rdLRZ6gK32Fxgs7hrMSioOmzjf9vzCI8DNAW0WufbslAG2mx0QIiS',
    'user_contact_no' => '',
    'remember_token' => 'hVlDgckyE7dCPxGcQH8g2nk32EK7d96qYlikhN1CTOyjfimddzKUMyaD4CKw',
    'department_id' => 32,
    'role_id' => 2,
    'college_id' => 1,
    'is_active' => 1,
    'deleted_at' => NULL,
    'created_at' => '2022-04-25T23:21:00.000000Z',
    'updated_at' => '2024-01-02T22:46:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 10,
    'name' => 'SHERAL ANITA PRAKASH',
    'email' => 'anitasheral3@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$/i3o9RvIPdy2TMPlotQnZeSEKOYZlSr/xHxCVhaUbbYBvumRxLs7y',
    'user_contact_no' => '',
    'remember_token' => 'r0edOlqCioJEoB2eZvljKewtSvTyYmxPJFXhJ7JkjmRaA14tMlYzMJupIaLW',
    'department_id' => 32,
    'role_id' => 2,
    'college_id' => 1,
    'is_active' => 1,
    'deleted_at' => NULL,
    'created_at' => '2022-04-25T23:31:00.000000Z',
    'updated_at' => '2023-09-01T22:42:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 12,
    'name' => 'Mr.Katke Yogesh',
    'email' => 'katkeyogesh@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$Vj.2.6gY3IAX9s0rQZ/BDuMngTQ9dAqFCS6piPcWUSfeuGoh5oAgq',
    'user_contact_no' => '',
    'remember_token' => 'FLfXJyCsXXvVzkuVMu4ahAaN5eb9WLVYpkj1Rz6YDqvkvcMPUhulRYkIUlWC',
    'department_id' => 32,
    'role_id' => 16,
    'college_id' => 1,
    'is_active' => 0,
    'deleted_at' => NULL,
    'created_at' => '2021-06-01T20:45:00.000000Z',
    'updated_at' => '2023-11-17T05:40:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 15,
    'name' => 'KADALE MARUTI RAMBHAU',
    'email' => 'marutikadale68@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$/AhcSE8lTbVZT19Vp5Su6u.u7Cbx.jTOXO5un1xmt2hbRHV1ywtE.',
    'user_contact_no' => '',
    'remember_token' => NULL,
    'department_id' => 32,
    'role_id' => 1,
    'college_id' => 1,
    'is_active' => 1,
    'deleted_at' => NULL,
    'created_at' => '2022-11-03T09:47:00.000000Z',
    'updated_at' => '2022-11-03T09:47:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 17,
    'name' => 'Vishal Malunjkar',
    'email' => 'malunjkar.vishal@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$Vj.2.6gY3IAX9s0rQZ/BDuMngTQ9dAqFCS6piPcWUSfeuGoh5oAgq',
    'user_contact_no' => '',
    'remember_token' => NULL,
    'department_id' => 1,
    'role_id' => 16,
    'college_id' => 1,
    'is_active' => 1,
    'deleted_at' => NULL,
    'created_at' => '2022-12-09T00:14:00.000000Z',
    'updated_at' => '2024-04-14T23:59:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 20,
    'name' => 'Misal Hanumant Ashruba',
    'email' => 'hanumantamisal@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$kWEoPDkJkcJeCJms/bsM4ey/JmDUk0DNR5uo/d1RGSrvZ1.X3dmEi',
    'user_contact_no' => '',
    'remember_token' => NULL,
    'department_id' => 32,
    'role_id' => 12,
    'college_id' => 1,
    'is_active' => 0,
    'deleted_at' => NULL,
    'created_at' => '2023-01-09T23:28:00.000000Z',
    'updated_at' => '2023-08-08T04:23:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 21,
    'name' => 'Sachin Chaudhari',
    'email' => 'sachinchaudhari99@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$2f2fIm9WEOy6v5wx36CYJe8pWYDA4pGmdj71AZ2G3wlDbxFzW8bne',
    'user_contact_no' => '',
    'remember_token' => NULL,
    'department_id' => 32,
    'role_id' => 12,
    'college_id' => 1,
    'is_active' => 1,
    'deleted_at' => NULL,
    'created_at' => '2023-01-10T00:31:00.000000Z',
    'updated_at' => '2023-12-03T22:11:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 22,
    'name' => 'Nawale S.V.',
    'email' => 'examwork@sangamnercollege.edu.in',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$0TiSI/Rm.fV1.BX2CnI2K.XeYY48rZX/ZoSLArSec4YIJQUd5FC0y',
    'user_contact_no' => '',
    'remember_token' => NULL,
    'department_id' => 32,
    'role_id' => 16,
    'college_id' => 1,
    'is_active' => 0,
    'deleted_at' => NULL,
    'created_at' => '2023-04-27T00:41:00.000000Z',
    'updated_at' => '2023-12-28T23:02:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 24,
    'name' => 'SHRI MAHALE S.S.',
    'email' => 'shreemahale@rediffmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$11PT0unS8hGZc3f1k/Xpp.k3eXL7oVhVY4yTHZCqVyC53oDqwY8pK',
    'user_contact_no' => '',
    'remember_token' => 'sLkRTUE2kpfDQcFy9jpqBKdmpumuGmVUNUO4pyeHeDNqcBKwu8in0kWCXvQP',
    'department_id' => 32,
    'role_id' => 9,
    'college_id' => 1,
    'is_active' => 0,
    'deleted_at' => NULL,
    'created_at' => '2023-05-02T00:39:00.000000Z',
    'updated_at' => '2023-12-28T23:01:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 25,
    'name' => 'Dighe Nitin lahanu',
    'email' => 'nitindighe94@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$Yr2QeaYvlX16PbLaMRY5ru3vV8ef.0xMMe6PuUO9H2mi99peI5k4m',
    'user_contact_no' => '',
    'remember_token' => 'kuALyFCuaf5R6gUd9zOOKs6tnDDPnLh3WVD7ZexkXWij2QxBARkEOhGXzQr5',
    'department_id' => 32,
    'role_id' => 12,
    'college_id' => 1,
    'is_active' => 0,
    'deleted_at' => NULL,
    'created_at' => '2023-05-09T04:07:00.000000Z',
    'updated_at' => '2023-05-09T05:52:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 26,
    'name' => 'GADEKAR AKSHAY',
    'email' => 'akshaygadekar333@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$13ISTqtwTwshHWFumZk5puYeqrCPBxR/Mi5P/2X7Z0y8fd7m4nyJ.',
    'user_contact_no' => '',
    'remember_token' => 'MDoSoBRyirJKNOuZkZ7CxhJkmpUA2grnp8n21XKbPBCxlLGl5LXeealmu8Qg',
    'department_id' => 32,
    'role_id' => 12,
    'college_id' => 1,
    'is_active' => 0,
    'deleted_at' => NULL,
    'created_at' => '2023-05-09T04:09:00.000000Z',
    'updated_at' => '2024-04-15T01:50:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 27,
    'name' => 'GAPALE RAVINDRA',
    'email' => 'gapaleravindra@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$U7a3dg/YxRY/uq5c7...qOM19srh4GsAKPxqkndo3LMvMwRYaXHFC',
    'user_contact_no' => '',
    'remember_token' => 'e9ecs1bM6OP2jKiF9I9QypWwDj3TEhbQYieqlXFHFUVFrr03BcGfGXXSCjO0',
    'department_id' => 32,
    'role_id' => 16,
    'college_id' => 1,
    'is_active' => 0,
    'deleted_at' => NULL,
    'created_at' => '2023-05-12T04:02:00.000000Z',
    'updated_at' => '2023-07-26T22:52:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 28,
    'name' => 'Smt. ABHALE J.K.',
    'email' => 'jk_abhale@rediffmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$JneztaRxoHDxXdtPH8mPxuAZKFegpFp1C/Sppufw8luLMokZztsEm',
    'user_contact_no' => '',
    'remember_token' => NULL,
    'department_id' => 32,
    'role_id' => 9,
    'college_id' => 1,
    'is_active' => 0,
    'deleted_at' => NULL,
    'created_at' => '2023-05-13T02:02:00.000000Z',
    'updated_at' => '2023-11-17T05:40:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 29,
    'name' => 'Mr. Sonawane Bhanudas',
    'email' => 'sonawane_bhanudas@rediffmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$11PT0unS8hGZc3f1k/Xpp.k3eXL7oVhVY4yTHZCqVyC53oDqwY8pK',
    'user_contact_no' => '',
    'remember_token' => NULL,
    'department_id' => 32,
    'role_id' => 9,
    'college_id' => 1,
    'is_active' => 1,
    'deleted_at' => NULL,
    'created_at' => '2023-05-15T06:03:00.000000Z',
    'updated_at' => '2024-04-15T01:32:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 31,
    'name' => 'MR. PAWAR D.S.',
    'email' => 'dipakpawar589@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$lgrknGkmz4s89q8JIyBroeWbM55t2rp7pfn87KPQ.QuDoaCeyfxky',
    'user_contact_no' => '',
    'remember_token' => NULL,
    'department_id' => 32,
    'role_id' => 9,
    'college_id' => 1,
    'is_active' => 0,
    'deleted_at' => NULL,
    'created_at' => '2023-05-23T06:16:00.000000Z',
    'updated_at' => '2023-07-18T04:09:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 32,
    'name' => 'MR. Kawade',
    'email' => 'kawade@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$lgrknGkmz4s89q8JIyBroeWbM55t2rp7pfn87KPQ.QuDoaCeyfxky',
    'user_contact_no' => '',
    'remember_token' => NULL,
    'department_id' => 32,
    'role_id' => 16,
    'college_id' => 1,
    'is_active' => 0,
    'deleted_at' => NULL,
    'created_at' => '2023-05-23T06:16:00.000000Z',
    'updated_at' => '2023-08-08T04:24:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 33,
    'name' => 'Malwade Sandip',
    'email' => 'info@sangamnercollege.edu.in',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$yAqpP1lEOI/Qb056RQjQyOKOND5qNM5xdjO96G3ZETpyreMAcAVDa',
    'user_contact_no' => '',
    'remember_token' => 'BYUUmaJ0JAa4Bnh4Jywrk4qNhXsMr4hSjHbkkEVQVmPqfZAxeLDGj6zcxXdC',
    'department_id' => 32,
    'role_id' => 12,
    'college_id' => 1,
    'is_active' => 0,
    'deleted_at' => NULL,
    'created_at' => '2023-10-10T23:32:00.000000Z',
    'updated_at' => '2023-10-20T09:43:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 34,
    'name' => 'KEDAR VILAS HARIBHAU',
    'email' => 'kedarvh22@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$IkFFtnvHkS.JKmf2byURJeQ8E3.q76AQLEPYoFXjhNdDo/Yfg5iyC',
    'user_contact_no' => '',
    'remember_token' => NULL,
    'department_id' => 6,
    'role_id' => 16,
    'college_id' => 1,
    'is_active' => 0,
    'deleted_at' => NULL,
    'created_at' => '2023-11-17T05:39:00.000000Z',
    'updated_at' => '2023-12-28T23:01:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 35,
    'name' => 'SHRI. BARDE R.B.',
    'email' => 'barderohidas2265@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$VZwwVNGYJhfKgfW7lIT5dO5dtnKePRiw4p1w1KDstpi6oclgzhrve',
    'user_contact_no' => '',
    'remember_token' => NULL,
    'department_id' => 34,
    'role_id' => 9,
    'college_id' => 1,
    'is_active' => 0,
    'deleted_at' => NULL,
    'created_at' => '2023-11-20T01:29:00.000000Z',
    'updated_at' => '2023-12-07T01:15:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 36,
    'name' => 'Gophane R.S.',
    'email' => 'skrajugophane@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$nYJsN/sKqE.INrKTf1csKeh4TKW2JxyfiuLS3ktmhznH2dlGXjdYe',
    'user_contact_no' => '',
    'remember_token' => NULL,
    'department_id' => 32,
    'role_id' => 12,
    'college_id' => 1,
    'is_active' => 0,
    'deleted_at' => NULL,
    'created_at' => '2023-12-01T04:57:00.000000Z',
    'updated_at' => '2023-12-28T23:00:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 37,
    'name' => 'PAWAR HRUSHIKESH JAGANNATH',
    'email' => 'shivnery@yahoo.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$htC8tUFV8S1b5IfqFimixO0SJDZIBRE760QZAY1cXWIkLDBhuaMfK',
    'user_contact_no' => '',
    'remember_token' => 'HEdMVmlkvgg5f2f0QPVPPOb8sW3AhLIlmR4wURSkT3W5QYazl3fbiyoYxmes',
    'department_id' => 19,
    'role_id' => 16,
    'college_id' => 1,
    'is_active' => 1,
    'deleted_at' => NULL,
    'created_at' => '2024-04-10T06:00:00.000000Z',
    'updated_at' => '2024-04-10T06:00:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 38,
    'name' => 'Account Dept',
    'email' => 'collegeac2018@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$10$oJcKjKLXrku5IRu5aHu28u3ZirJbAWQexoGDIJJXlRwJvp8WrXjPO',
    'user_contact_no' => '',
    'remember_token' => '1JRZAaaYlbHwX29J7PfOrmRIhAqPxjn2IFFHVU8zvqHDXFKAazYeBkStDxmn',
    'department_id' => 32,
    'role_id' => 13,
    'college_id' => 1,
    'is_active' => 1,
    'deleted_at' => NULL,
    'created_at' => '2024-04-15T02:14:00.000000Z',
    'updated_at' => '2024-04-15T02:14:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 39,
    'name' => 's',
    'email' => 'sitaram.kawade23@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$12$9XiyBvMdvZNBXxdhfgtICOABpuz73ePsGvr.hbw4f.pvjEnufE6Wu',
    'user_contact_no' => '',
    'remember_token' => NULL,
    'department_id' => 32,
    'role_id' => 1,
    'college_id' => 1,
    'is_active' => 0,
    'deleted_at' => NULL,
    'created_at' => '2024-04-21T06:53:00.000000Z',
    'updated_at' => '2024-04-21T06:53:00.000000Z',
  ));
  
  
  User::create(array (
    'id' => 40,
    'name' => 'user',
    'email' => 'user@gmail.com',
    'email_verified_at' => '2024-04-26T04:02:00.000000Z',
    'password' => '$2y$12$Jv6JmRH7ahnqW7RI1Zkctep3RYpV1Np2hdjjwyhxkmOU/Nug6kDO.',
    'user_contact_no' => 'MTIzNDU2Nzg5MA==',
    'remember_token' => 'yUKjkNYV0z',
    'department_id' => 32,
    'role_id' => 1,
    'college_id' => 1,
    'is_active' => 1,
    'deleted_at' => NULL,
    'created_at' => '2024-04-02T12:49:30.000000Z',
    'updated_at' => '2024-04-01T12:49:34.000000Z',
  ));
  
  
    }
}
