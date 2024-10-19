<!DOCTYPE html>
<html>
    <body>
        <?php
           
            $arquivo = isset($_FILES["arquivo"]) ? $_FILES["arquivo"] : false;

            if ($arquivo) {
                
                $nome = strtolower(str_replace(" ", "_", $arquivo["name"]));
                $mime = $arquivo['type'];
                $tamanho = $arquivo['size'];
                $temp = $arquivo['tmp_name'];
                $erros = $arquivo['error'];

             
                $max_tamanho = 30000;
                $tipo = "image/png";  
                $uploaddir = 'upload/';
                $uploadfile = $uploaddir . $nome;

               
                if (file_exists($uploadfile)) {
                    echo "Ops! Já existe um arquivo chamado " . $nome . " na pasta " . $uploaddir . ". Tente de novo.";
                    exit;
                }

             
                if ($tamanho > $max_tamanho) {
                    echo "Ops! O arquivo enviado ultrapassa o tamanho máximo de " . $max_tamanho . " bytes. Tente de novo.";
                    exit;
                }

               
                if ($mime !== $tipo) {
                    echo "Ops! O tipo MIME do arquivo enviado não é " . $tipo . ". Tente de novo.";
                    exit;
                }

             
                if (move_uploaded_file($temp, $uploadfile)) {
                    echo "Upload efetuado com sucesso!";
                    echo "<br><br>";
                    echo "Nome original do arquivo: " . $nome;
                    echo "<br>";
                    echo "Tipo MIME do arquivo: " . $mime;
                    echo "<br>";
                    echo "Tamanho do arquivo em bytes: " . $tamanho;
                    echo "<br>";
                    echo "Nome temporário do arquivo: " . $temp;
                    echo "<br>";
                    echo "Código do erro ocorrido durante o upload do arquivo: " . $erros;
                } else {
                    echo "Ops! Houve uma falha no processo de upload do arquivo!";
                }
            } else {
                echo "Nenhum arquivo foi enviado.";
            }
        ?>
    </body>
</html>
