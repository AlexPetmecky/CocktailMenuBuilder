<?php
require('fpdf186/fpdf.php');

require_once './RequestHandler/DrinkRequests.php';
require_once './RequestHandler/MenuHandler.php';

class PDFmanager{
    private DrinkRequests $requestController;
    private MenuHandler $menuHandler;
    function __construct(){
        $this->requestController = new DrinkRequests();
        $this->menuHandler = new MenuHandler();

    }

    public function makePDF(String $titleName,$menuName,$uID){
        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 18);
        $pdf->Cell(60,20,$menuName,0,1,'C');

        $drinkString = $this->menuHandler->getDrinksFromMenu($uID,$menuName);
        $drinkIDArray = explode("/",$drinkString);
        //print_r($drinkIDArray);
        $lineNum = 2;

        $Y = 40;




        $maxheight = 0;
        $cells = 2;
        $width = 75;
        $height=10;
        foreach ($drinkIDArray as $drinkID){
            if ($drinkID != null && !empty($drinkID)){
                //print_r(intval($drinkID));
                $drinkRecipe = $this->requestController->getFullRecipeByID(intval($drinkID));
                $name = $drinkRecipe["name"];
                $ingredients = $drinkRecipe["ingredients"];
                //print_r($ingredients);
                $instructions = $drinkRecipe["instructions"];
                //$addString = $name."\n".implode(", ",$ingredients["ingredsMain"])." Garnish: ".implode(",",$ingredients["garnishes"]);

                $ingredString = implode(", ",$ingredients["ingredsMain"])." Garnish: ".implode(",",$ingredients["garnishes"]);
                $pdf->SetFont('Arial', 'B', 16);

                //$pdf->MultiCell(0,10,$name,0,1);


                $pdf->MultiCell(0,10,$name,0,1);


                $pdf->SetFont('Arial', '', 14);
                $y = $pdf->GetY();
                $pdf->MultiCell(75,10,$ingredString,0,'l');

                $x = $pdf->GetX();

                $pdf->SetXY($x+75,$y);
                $pdf->MultiCell(100, 10, $instructions , 0, 'l');
                $pdf->Ln(10);



/*
                $x = $pdf->GetX();
                $y = $pdf->GetY();

                $data = [$ingredString,$instructions];
                for ($i = 0; $i < $cells; $i++) {
                    $pdf->MultiCell($width, $height, $data[$i]);
                    if ($pdf->GetY() - $y > $maxheight) $maxheight = $pdf->GetY() - $y;
                    $pdf->SetXY($x + ($width * ($i + 1)), $y);
                }

                for ($i = 0; $i < $cells + 1; $i++) {
                    $pdf->Line($x + $width * $i, $y, $x + $width * $i, $y + $maxheight);
                }

                $pdf->Line($x, $y, $x + $width * $cells, $y);
                $pdf->Line($x, $y + $maxheight, $x + $width * $cells, $y + $maxheight);
                */

                /*
                $yPrev = $pdf->GetY();

                $pdf->SetXY(0, $Y);
                $pdf->SetFont('Arial', '', 14);
                $pdf->SetXY(0, $Y);
                $pdf->MultiCell(75,10,$ingredString,1,'l');

                $x = $pdf->GetX();
                $y = $pdf->GetY();

                $pdf->SetXY($x + 75, $Y);
                $pdf->MultiCell(100, 10, $instructions , 1, 'l');
                //$pdf->CellFit();
                $H = $pdf->GetY();
                $height= $H+$Y-10;
                //$height= $H+$yPrev;
                $Y=$height;
                */
            }else{
                //print_r("HERE");
            }


            $lineNum ++;
        }



        $fname = uniqid();
        $pdf->Output("f", "./PDFs/".$fname . ".pdf");

        return $fname;


    }

    public function makeIngredientsPDF(String $titleName,$menuName,$uID,$ingredientString,$garnishString){
        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 18);
        $pdf->Cell(60,20,$menuName,0,1,'C');

        //$drinkString = $this->menuHandler->getDrinksFromMenu($uID,$menuName);
        //$drinkIDArray = explode("/",$drinkString);
        //print_r($drinkIDArray);
        $lineNum = 2;

        $Y = 40;




        $maxheight = 0;
        $cells = 2;
        $width = 75;
        $height=10;

        $ingredientArray = explode(",",$ingredientString);
        $garnishArray = explode(",",$garnishString);

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(60,20,"Ingredients",0,1,"L");

        $pdf->SetFont('Arial', '', 12);

        /*
        foreach ($ingredientArray as $ingredient){
            $pdf->Cell(60,20,$ingredient,0,1,"L");
        }
        */

        //////////INGREIDENTS HERE////////////////////////////////////////////

        $MAX_CELLS = 3;
        $curr_cells=0;
        $y = $pdf->GetY();
        foreach ($ingredientArray as $ingredient){
            if ($ingredient != null && !empty($ingredient)) {
                //print_r(intval($drinkID));
                //$pdf->SetFont('Arial', 'B', 16);

                $x = $pdf->getX();
                //$y=$pdf->getY();
                $pdf->SetXY($x + (50 * $curr_cells), $y);

                //$pdf->SetFont('Arial', '', 14);

                $pdf->MultiCell(75, 10, $ingredient, 0, 'l');

                $curr_cells ++;
                if($curr_cells == 3){
                    $curr_cells = 0;
                    $pdf->Ln(10);
                    $y = $pdf->GetY();
                }

            }
        }




        ///////////////////////////////////////////////////////////////

        $pdf->Ln(15);

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(60,20,"Garnishes",0,1,"L");
        $pdf->SetFont('Arial', '', 12);


        ////GARNISHES HERE//////////////////////////////////////////////////////
        $MAX_CELLS = 3;
        $curr_cells=0;
        $y = $pdf->GetY();
        foreach ($garnishArray as $garnish){
            if ($garnish != null && !empty($garnish)) {
                //print_r(intval($drinkID));
                //$pdf->SetFont('Arial', 'B', 16);

                $x = $pdf->getX();
                //$y=$pdf->getY();
                $pdf->SetXY($x + (50 * $curr_cells), $y);

                //$pdf->SetFont('Arial', '', 14);

                $pdf->MultiCell(75, 10, $garnish, 0, 'l');

                $curr_cells ++;
                if($curr_cells == 3){
                    $curr_cells = 0;
                    $pdf->Ln(10);
                    $y = $pdf->GetY();
                }

            }
        }

        //////////////////////////////////////////////////////////


        /*
        $pdf->SetFont('Arial', '', 16);
        $pdf->Cell(60,20,"Garnishes",0,1,"L");

        $pdf->SetFont('Arial', '', 12);
        foreach ($garnishArray as $garnish){
            $pdf->Cell(60,20,$garnish,0,1,"L");
        }
        */

        $fname = uniqid();
        $pdf->Output("f", "./PDFs/".$fname . ".pdf");

        return $fname;


    }


}