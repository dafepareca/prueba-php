<?php

namespace App\Controller;

class LogTransaccionController extends AppController
{
    public static function EscribirLog ($mensaje, $parametros = null,$ex = null)
    {
        try{
            $tipo = $ex==null?'TRCE':'FAIL';
            $nameFile = 'registro_'.date('YmdH').'.log';
            $directorio = TMP.'logs/'.$tipo.'/'.date('Y').'/'.date('m'). '/'.date('d');
            
            if (!file_exists($directorio)) {
                mkdir($directorio,0777,TRUE);
            }
            
            $filename = $directorio.'/'.$nameFile;
            $file = fopen($filename, 'a+');
            if (flock($file, LOCK_EX)) {  // adquirir un bloqueo exclusivo
                fwrite($file, "=============================" . PHP_EOL);
                fwrite($file, 'Fecha/hora '.date('Y-m-d H:i:s'). '.' . PHP_EOL);
                fwrite($file, 'Mensaje:'.$mensaje.'.'. PHP_EOL);
                if (!empty($parametros) || $parametros != null){
                    $parametros = json_encode($parametros);
                    fwrite($file, 'Parametros:'.$parametros.'.' . PHP_EOL);
                }
                if (!empty($ex) || $ex != null){
                    fwrite($file, 'Excepciones:'.$ex.'.' . PHP_EOL);
                }
                fflush($file);            // volcar la salida antes de liberar el bloqueo
                flock($file, LOCK_UN);    // libera el bloqueo
            } else {
                \Cake\Log\Log::error("No se pudo obtener el bloqueo");
            }
            fclose($file);

        }catch (Exception $th) {
            \Cake\Log\Log::error($th);
        }
    }

    public static function EscribirTiempo ($transaccion,$operacion,$clase,$metodo,$idLogL,$duracion)
    {
        try{
            $tipo = 'TIME';
            $nameFile = 'registro_'.date('YmdH').'.log';
            $directorio = TMP.'logs/'.$tipo.'/'.date('Y').'/'.date('m'). '/'.date('d');
            
            if (!file_exists($directorio)) {
                mkdir($directorio,0777,TRUE);
            }
            $filename = $directorio.'/'.$nameFile;
            $horas = (int) ($duracion / 60 / 60);
            $minutos = (int) ($duracion / 60) - $horas * 60;
            $segundos = (int) $duracion - $horas * 60 * 60 - $minutos * 60;
            $mili = (int)($duracion * 1000);
            $tiempo = ($horas == 0 ? "00":$horas) . ":" . ($minutos == 0 ? "00":($minutos < 10? "0".$minutos:$minutos)) . ":" . ($segundos == 0 ? "00":($segundos < 10? "0".$segundos:$segundos)) . "." . $mili;
            $parametros = $tiempo. '; '. $transaccion.'; '.$operacion.'; '.$clase.'; '.$metodo.'; '.$idLogL;
            $file = fopen($filename, 'a+');
            if (flock($file, LOCK_EX)) {  // adquirir un bloqueo exclusivo
                fwrite($file, date('Y-m-d H:i:s'). '; '.$parametros .';' . PHP_EOL);
                fflush($file);            // volcar la salida antes de liberar el bloqueo
                flock($file, LOCK_UN);   // libera el bloqueo
            } else {
                \Cake\Log\Log::error("No se pudo obtener el bloqueo");
            }
            fclose($file);

        }catch (Exception $th) {
            \Cake\Log\Log::error($th);
        }
    }
    public static function EscribirLogHistory ($mensaje, $parametros)
    {
        try{
            $nameFile = 'Historyregistro_'.date('YmdH').'.log';
            $directorio = TMP.'logs/history/'.date('Y').'/'.date('m'). '/'.date('d');
            
            if (!file_exists($directorio)) {
                mkdir($directorio,0777,TRUE);
            }
            
            $filename = $directorio.'/'.$nameFile;
            $file = fopen($filename, 'a+');
            if (flock($file, LOCK_EX)) {  // adquirir un bloqueo exclusivo
                fwrite($file, "=============================" . PHP_EOL);
                fwrite($file, 'Fecha/hora '.date('Y-m-d H:i:s'). '.' . PHP_EOL);
                fwrite($file, 'Mensaje:'.$mensaje.'.'. PHP_EOL);
                if (!empty($parametros) || $parametros != null){
                    $parametros = json_encode($parametros);
                    fwrite($file, 'Parametros:'.$parametros.'.' . PHP_EOL);
                }
                fflush($file);            // volcar la salida antes de liberar el bloqueo
                flock($file, LOCK_UN);    // libera el bloqueo
            } else {
                \Cake\Log\Log::error("No se pudo obtener el bloqueo");
            }
            fclose($file);

        }catch (Exception $th) {
            \Cake\Log\Log::error($th);
        }
    }
}
