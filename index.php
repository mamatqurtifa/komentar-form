<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Komentar</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  </head>
    
  <body>
      
    <!--  Konten utama -->
    <div class="container mx-auto p-4">
      <div class="flex justify-center">
        <div class="w-full max-w-md">
          
          <!-- Tombol Tambah Komentar -->
          <button id="btnTambahKomentar" class="inline-flex items-center px-4 py-2 mb-4 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Tambah Komentar</button>

          <!-- Form Tambah Komentar -->
          <form method="post" id="form_komen" class="hidden">
            <div class="mb-3 animate-box">
              <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
              <input type="text" class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="nama" aria-describedby="nama" name="nama" autocomplete="off" required>
            </div>
 
            <div class="mb-3 animate-box">
              <label for="komentar" class="block text-sm font-medium text-gray-700">Komentar</label>
              <textarea class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="komentar" name="komentar" rows="3" style="height: 100px;" required></textarea>
            </div>
 
            <input type="hidden" name="parent_id" id="parent_id" value="0" />
            <button type="submit" name="submit" id="submit" class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 animate-box">Kirim</button>
          </form>
 
          <!-- Hasil komentar -->
          <div class="overflow-auto border-2 border-gray-300 p-2 mt-5 max-h-96 bg-gray-100">
            <div id="komentarPengguna"></div>
          </div>
          <!-- </Akhir hasil komentar -->
          <div class="flex justify-start items-center flex-col">
            <p>Are you an admin?</p>
            <a href="admin_dashboard.php">click this</a>
          </div>
        </div>
      </div>
    </div>
      
    <!-- </Akhir konten utama-->
      
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.js"></script>
      
    <!-- Jquery 3.6.3-->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
      
    <!-- Script jquery -->
    <script>
      // ketika dokumen sudah siap
      $(document).ready(function(){
        
        // Show the form when "Tambah Komentar" button is clicked
        $('#btnTambahKomentar').click(function(){
          $('#form_komen').toggleClass('hidden');
        });

        // load function komentar ketika dokumen sudah siap
        loadKomentar();
          
        // ambil id form komen 
        $('#form_komen').on('submit', function(e) {
          /*  
          e.preventDefault() di sini di maksud kan agar data bisa di kirim ke server 
          tanpa refresh halaman menggunakan ajax nanti
          */
          e.preventDefault();

          // Array of prohibited words
          const prohibitedWords = ["kontol", "gay", "pepek", "penis", "peler", "payudara", "venis", "feler", "bajingan", "goblok", "tolol", "jancok", "asu", "shit", "kon", "kont", "konto", "puting", "ngen", "ngentot", "bersetubuh", "cocot", "cocote", "edan", "jancuk", "kopet", "anjing", "anj", "bacot", "crot", "gundul", "gendeng", "gendheng", "utek", "pusy", "pussy", "kemaluan", "fuck", "bitch", "lonte", "nigger", "nigga", "negro", "nigro", "cabul", "pantat", "bokong", "jembut", "porno", "porn", "cp", "childporn", "crt", "lendir", "mani", "titit"];
          
          // Get the name and comment text
          const name = $('#nama').val().toLowerCase();
          const comment = $('#komentar').val().toLowerCase();

          // Check for prohibited words in name
          for (let word of prohibitedWords) {
            if (name.includes(word)) {
              alert("Maaf, anda mengirim kata-kata yang dilarang di nama, harap kirim nama yang baik");
              return;
            }
          }

          // Check for prohibited words in comment
          for (let word of prohibitedWords) {
            if (comment.includes(word)) {
              alert("Maaf, anda mengirim kata-kata yang dilarang di komentar, harap kirim komentar yang baik");
              return;
            }
          }
 
          // init ajax
          $.ajax({
            // set url
            url: "tambah_komentar.php",
            
            // set method
            method: "POST",
            
            // ambil semua data form
            data: $(this).serialize(),
            
            // ketika sukses dan data berhasil masuk
            success: function(response) {
              // load function komentar
              loadKomentar();
              
              // reset form komen
              $('#form_komen')[0].reset();

              // hide the form
              $('#form_komen').addClass('hidden');
              
              // di bawah ini jika pengguna tidak membalas komentar pengguna lain
              $('#parent_id').val('0');
            }
          })
        });
          
        // untuk mendapatkan ID dari setiap pengguna dan membalas komentar
        $(document).on('click', '.reply', function () {
          let id = $(this).attr("id");
          $('#parent_id').val(id);
          $('#nama').focus();
        });
          
        // function load komentar
        function loadKomentar(){
          // init ajax
          $.ajax({
            // set url
            url: "ambil_komentar.php",
            
            // set method
            method: "GET",
            
            // ketika data berhasil di GET
            success: function(response) {
              // tampilkan pada tag div id komentar pengguna
              $('#komentarPengguna').html(response);
            }
          })
        }
      });
    </script>
  </body>
</html>
