<?php

require('fpdf/fpdf.php');


class PDF extends FPDF
{

    function Header()
    {
        // Logo
        $this->Image('FA Logo.jpg',10,10,20);
        // Arial bold 15
        $this->SetFont('Arial','B',10);
        // Move to the right
        $this->Cell(30);
        $this->Cell(30,10,'Faculty Association,Inc.',0,0,'L');
        $this->Ln(5);
        $this->Cell(30);
        $this->SetFont('Arial','',10);
        $this->Cell(30,10,'De La Salle University - Manila',0,0,'L');
        $this->Ln(20);
    }

    function WordWrap(&$text, $maxwidth)
    {
        $text = trim($text);
        if ($text==='')
            return 0;
        $space = $this->GetStringWidth(' ');
        $lines = explode("\n", $text);
        $text = '';
        $count = 0;

        foreach ($lines as $line)
        {
            $words = preg_split('/ +/', $line);
            $width = 0;

            foreach ($words as $word)
            {
                $wordwidth = $this->GetStringWidth($word);
                if ($wordwidth > $maxwidth)
                {
                    // Word is too long, we cut it
                    for($i=0; $i<strlen($word); $i++)
                    {
                        $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
                        if($width + $wordwidth <= $maxwidth)
                        {
                            $width += $wordwidth;
                            $text .= substr($word, $i, 1);
                        }
                        else
                        {
                            $width = $wordwidth;
                            $text = rtrim($text)."\n".substr($word, $i, 1);
                            $count++;
                        }
                    }
                }
                elseif($width + $wordwidth <= $maxwidth)
                {
                    $width += $wordwidth + $space;
                    $text .= $word.' ';
                }
                else
                {
                    $width = $wordwidth + $space;
                    $text = rtrim($text)."\n".$word.' ';
                    $count++;
                }
            }
            $text = rtrim($text)."\n";
            $count++;
        }
        $text = rtrim($text);
        return $count;
    }

    function Justify($text, $w, $h)
    {
        $tab_paragraphe = explode("\n", $text);
        $nb_paragraphe = count($tab_paragraphe);
        $j = 0;

        while ($j<$nb_paragraphe) {

            $paragraphe = $tab_paragraphe[$j];
            $tab_mot = explode(' ', $paragraphe);
            $nb_mot = count($tab_mot);

            // Handle strings longer than paragraph width
            $k=0;
            $l=0;
            while ($k<$nb_mot) {

                $len_mot = strlen ($tab_mot[$k]);
                if ($len_mot<($w-5) )
                {
                    $tab_mot2[$l] = $tab_mot[$k];
                    $l++;
                } else {
                    $m=0;
                    $chaine_lettre='';
                    while ($m<$len_mot) {

                        $lettre = substr($tab_mot[$k], $m, 1);
                        $len_chaine_lettre = $this->GetStringWidth($chaine_lettre.$lettre);

                        if ($len_chaine_lettre>($w-7)) {
                            $tab_mot2[$l] = $chaine_lettre . '-';
                            $chaine_lettre = $lettre;
                            $l++;
                        } else {
                            $chaine_lettre .= $lettre;
                        }
                        $m++;
                    }
                    if ($chaine_lettre) {
                        $tab_mot2[$l] = $chaine_lettre;
                        $l++;
                    }

                }
                $k++;
            }

            // Justified lines
            $nb_mot = count($tab_mot2);
            $i=0;
            $ligne = '';
            while ($i<$nb_mot) {

                $mot = $tab_mot2[$i];
                $len_ligne = $this->GetStringWidth($ligne . ' ' . $mot);

                if ($len_ligne>($w-5)) {

                    $len_ligne = $this->GetStringWidth($ligne);
                    $nb_carac = strlen ($ligne);
                    $ecart = (($w-2) - $len_ligne) / $nb_carac;
                    $this->_out(sprintf('BT %.3F Tc ET',$ecart*$this->k));
                    $this->MultiCell($w,$h,$ligne);
                    $ligne = $mot;

                } else {

                    if ($ligne)
                    {
                        $ligne .= ' ' . $mot;
                    } else {
                        $ligne = $mot;
                    }

                }
                $i++;
            }

            // Last line
            $this->_out('BT 0 Tc ET');
            $this->MultiCell($w,$h,$ligne);
            $tab_mot = '';
            $tab_mot2 = '';
            $j++;
        }
    }

    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

}

?>