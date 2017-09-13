<?php

/*
 *  Author: Suwandi Tan ( swndtan@gmail.com )
 *  
 *  License: Apache License 2
 *  Copyright 2012 Suwandi Tan
 *
 *  Indonesia:
 *  Lisensi dibawah lisensi Apache, versi 2 ( "LISENSI" );
 *  Kamu tidak boleh menggunakan berkas ini kecuali tunduk dengan lisensi ini.
 *  Kamu bisa dapatkan salinan dari lisensi ini di
 *    
 *       http://www.apache.org/licenses/LICENSE-2.0
 *    
 *  Kecuali diwajibkan oleh hukum yang berlaku atau disetujui secara tertulis,
 *  piranti lunak dibawah LISENSI ini disebarluaskan "APA ADANYA", TANPA GARANSI 
 *  ATAU SYARAT APAPUN, baik tersirat maupun tersurat.
 *  Lihat lisensi ini lebih lengkap dalam bahasa kamu mengenai perijinan dan
 *  keterbatasan lisensi ini.
 *
 *  English: 
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

{
    function settings( $config = NULL )
    {
        if( is_null( $config ) ) $config = __DIR__.'/settings.json';
        if( ! file_exists( $config ) ) 
        {
            throw new Exception( 'Settings file \''.$config.'\' configuration not found.' );
        }
        
        $conf = json_decode( file_get_contents( $config ), TRUE );
        $default = $conf[ $conf['default'] ];
        
        return array( 
            $default['ones'], $default['tens'], $default['twenties'], $default['thousands'], 
            $default['hundreds'], $conf['default']
        );
    }
    
    function triplet( $num )
    {
        $n3 = array();
        $ns = ( string ) sprintf( '%33.0f', $num );
        
        for( $i=3; $i<34; $i+=3 )
        {
            $r = substr( $ns, -$i );
            $q = strlen( $ns ) - $i;
            
            if( $q < -2 ) break;
            else
            {
                if( $q >= 0 ) $n3 [] = ( float ) substr( $r, 0, 3 );
                elseif( $q >=-1 ) $n3 [] = ( float ) substr( $r, 0, 2 );
                elseif( $q >=-2 ) $n3 [] = ( float ) substr( $r, 0, 1 );
            }
        }
        
        return $n3;
    }
    
    function specificIdFormat()
    {
        $args   = func_get_args();
        
        $args   = $args[0];
        $ones   = $args[0];
        $d1     = $args[1];
        $i      = $args[2];
        
        if( is_null( $i ) ) return ( ( isset( $ones[ $ones[$d1] ] ) )? $ones[ $ones[$d1] ] : $ones[$d1] );
        return ( ( isset( $ones[ $ones[$d1] ] ) AND $i<2 )? $ones[ $ones[$d1] ] : $ones[$d1] );
    }
    
    function specificEnFormat()
    {
        $args   = func_get_args();
        
        $args   = $args[0];
        $ones   = $args[0];
        $d1     = $args[1];
        
        return $ones[$d1];
    }
    
    function specificLangFormat( $lang )
    {
        $args = func_get_args();
        array_shift( $args );
        
        switch( $lang )
        {
            case 'id':
                return specificIdFormat( $args );
            case 'en':
                return specificEnFormat( $args );
                
            default:
                throw new Exception( 'No specific language defined.' );
        }
    }
    
    function num2words( $num, $suffix = '', $config = NULL )
    {
        list( $ones, $tens, $twenties, $thousands, $hundreds, $lang ) = settings( $config );
        
        $n3 = triplet( $num );
        $nw = '';
        foreach( $n3 as $i => $x )
        {
            $d1 = floor( $x % 10 );
            $d2 = floor( ( $x % 100 ) / 10 );
            $d3 = floor( ( $x % 1000 ) / 100 );
            
            if( $x == 0 ) continue;
            else $t = $thousands[ $i ];
            
            if( $d2 == 0 ) $nw = sprintf( '%s%s%s', 
                specificLangFormat( $lang, $ones, $d1, $i ),
                $t, $nw 
            );
            
            elseif( $d2 == 1 ) $nw = sprintf( '%s%s%s', $tens[$d1], $t, $nw );
            elseif( $d2 > 1 ) $nw = sprintf( '%s%s%s%s', $twenties[$d2], 
                specificLangFormat( $lang, $ones, $d1, $i ),
                $t, $nw 
            );
            
            if( $d3 > 0 ) $nw = sprintf( '%s%s%s', 
                specificLangFormat( $lang, $ones, $d3, NULL ),
                $hundreds, $nw 
            );
        }
        
        return $nw.$suffix;
    }
}
