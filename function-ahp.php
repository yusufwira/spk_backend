<?php

require_once __DIR__ . '/vendor/autoload.php';
use Phpml\Math\Matrix;

define('ROUND', 4);
define('THRESHOLD', 0.0001);

function ahp_process(Matrix $matrix, bool $isDebug = FALSE)
{
    if($isDebug)
    {
        echo '<div class="mb-5"><h4>1. INITIAL MATRIX</h4>';
        $tempMatrix = $matrix->toArray();
        echo '<table border=1>';
        for($i = 0; $i < $matrix->getRows(); $i++)
        {
            echo '<tr align=center>';
            for($j = 0; $j < $matrix->getColumns(); $j++)
            {
                echo '<td width=70px>'.round($tempMatrix[$i][$j], ROUND).'</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
    }

    if($isDebug) $eigenVector = (isMatrixConsistent($matrix, TRUE))? getEigenVector($matrix, FALSE) : getValidEigenVector($matrix, TRUE);
    else $eigenVector = (isMatrixConsistent($matrix))? getEigenVector($matrix) : getValidEigenVector($matrix);

    if($isDebug)
    {
        if($eigenVector != null &&  isMatrixConsistent($matrix))
        {
            echo '<div><h4>3. RESULT</h4>';
            echo '<table border=1 class="mt-2">';
            echo '<tr align=center><td width=150px><i>Final Eigen Vector</i></td></tr>';
            for($i = 0; $i < $eigenVector->getRows(); $i++)
            {
                echo '<tr align=center><td>'.round(($eigenVector->toArray())[$i][0], ROUND).'</td></tr>';
            }
            echo '</table>';
            echo '</div>';
        }
    }

    return $eigenVector;
}


// -------- //
function isMatrixConsistent(Matrix $matrix, bool $isDebug = FALSE)
{
    $tempMatrix = [];

    if($isDebug) 
    {
        echo '<div><h4>2. MATRIX CONSISTENCY CHECK</h4><b>(per column)</b><br><br>';
        echo '<div class="mb-2">';
    }
    for($i = 0; $i < $matrix->getColumns(); $i++)
    {
        $totalPerCol = 0.0;
        foreach($matrix->getColumnValues($i) as $colValue)
        {
            $totalPerCol += $colValue;
        }

        if($isDebug) 
        {
            echo '<div style="display: inline-block;">';
            echo '<table border=1>';
            echo '<tr align=center><th colspan=2><i>Column #'.($i+1).'</i></th></tr>';
        }

        $j = 0;
        foreach($matrix->getColumnValues($i) as $colValue)
        {
            $normal = round($colValue / $totalPerCol, ROUND);
            $tempMatrix[$i][] = $normal;
            
            if($isDebug)
            {
                echo '<tr>
                        <td>Col '.($i+1).' Row '.($j+1).'</td>
                        <td align=center>'.round($colValue, ROUND).'</td>
                      </tr>';
            }
            $j++;
        }

        if($isDebug)
        {
            echo '<tr><td colspan=2><span></span></td></tr>';
            echo '<tr>
                    <td width=200px><i>Total per Column</i></td>
                    <td width=80px height=40px align=center><i>'.round($totalPerCol, ROUND).'</i></td>
                  </tr>';
            echo '<tr><td colspan=2><span></span></td></tr>';
        }

        $j = 0;
        foreach($matrix->getColumnValues($i) as $colValue)
        {
            if($isDebug)
            {
                echo '<tr>
                        <td>Normalized Col '.($i+1).' Row '.($j+1).'</td>
                        <td align=center>'.round($colValue / $totalPerCol, ROUND).'</td>
                     </tr>';
            }
            $j++;
        }

        if($isDebug)
        {
            echo '</table>';
            echo '</div>';
        }
    }
    if($isDebug) echo '</div>';

    
    $tempMatrix = new Matrix($tempMatrix);

    if($isDebug) echo '<div class="mb-5"><b>Matrix Consistency Test Result : </b>';
    for($i = 0; $i < $tempMatrix->getColumns(); $i++)
    {
        $temp = null;
        foreach($tempMatrix->getColumnValues($i) as $colValue)
        {
            if($temp == null)
            {
                $temp = $colValue;
                continue;
            }

            if($temp != $colValue) 
            {
                if($isDebug) 
                {
                    echo 'INCONSISTENT<br>';
                    echo '<b>Reason :</b> each <u>normalized value</u> in the <u>same row</u> does NOT match<br>';
                    echo '> ITERATIONS <i>REQUIRED</i></div>';
                }
                return false;
            }
            else $temp = $colValue;
        }
    }

    if($isDebug) echo '<b>CONSISTENT</b></div>';
    return true;
}

function getEigenVector(Matrix $squaredMatrix, bool $isDebug = FALSE)
{
    $VE = [];

    $squareMtxArr = $squaredMatrix->toArray();
    for($i = 0; $i < count($squareMtxArr); $i++)
    {
        $colvalue = 0.0;
        for($j = 0; $j < count($squareMtxArr[$i]); $j++)
        {
            $colvalue += $squareMtxArr[$i][$j];
        }
        // push to eigen array
        $VE[$i][0] = $colvalue;
    }

    if($isDebug) return normalizeEigen(new Matrix($VE), TRUE);
    else return normalizeEigen(new Matrix($VE));
}

function normalizeEigen(Matrix $eigenMatrix, bool $isDebug = FALSE)
{
    if($eigenMatrix->getColumns() > 1) throw new Exception("Invalid matrix's dimension.");

    $total = 0.0;
    foreach($eigenMatrix->getColumnValues(0) as $eigenValue)
    {
        $total += $eigenValue;
    }

    $normalized = $eigenMatrix->toArray();
    if($isDebug)
    {
        echo '<div><b>Eigen Vector</b>';
        echo '<table border=1>';
    }
    for($i = 0; $i < count($normalized); $i++)
    {
        $normalized[$i][0] = $normalized[$i][0] / $total;
        if($isDebug) echo '<tr><td>'.round($normalized[$i][0], ROUND).'</td></tr>';
    }
    if($isDebug) echo '</table></div>';

    return new Matrix($normalized);
}


// -------- //
function getValidEigenVector(Matrix $matrix, bool $isDebug = FALSE)
{
    $matrixes = [];
    $matrixes[] = $matrix;
    // $eigenVector = [];
    
    if($isDebug) echo '<div class="mb-5"><h4>3. ITERATIONS</h4><b>(threshold = '.THRESHOLD.')</b><br><br>'; // title

    $idx = 0;
    do {
        $eigenVector = getEigenVector($matrixes[$idx]);

        $matrixes[] = $matrixes[$idx]->multiply($matrixes[$idx]);
        $idx++;

        $deltaEigenVector = getDeltaEigenVector($eigenVector, getEigenVector($matrixes[$idx]));

        if($isDebug)
        {
            echo '<div class="mb-3">';
            echo '<h5><i>Iteration #'.$idx.'</i></h5>';


            // print matrix (twice)
            $tempMatrix = $matrixes[$idx-1]->toArray();
            for($i = 0; $i < 2; $i++)
            {
                echo '<table border=1 style="display: inline-block;">';
                echo '<tr align=center><td colspan=100><i>Matrix</i></td></tr>';
                for($j = 0; $j < $matrixes[$idx-1]->getRows(); $j++)
                {
                    echo '<tr align=center>';
                    for($k = 0; $k < $matrixes[$idx-1]->getColumns(); $k++)
                    {
                        echo '<td width=70px>'.round($tempMatrix[$j][$k], ROUND).'</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
                if($i == 0)
                {
                    echo '<p class="mx-3 mb-0" style="display: inline-block;"> x </p>';
                }
            }

            echo '<p class="mx-3 mb-0" style="display: inline-block;"> = </p>';


            // print squared matrix
            $tempMatrix = $matrixes[$idx]->toArray();
            echo '<table border=1 style="display: inline-block;">';
            echo '<tr align=center><td colspan=100><i>Squared Matrix</i></td></tr>';
            for($i = 0; $i < $matrixes[$idx]->getRows(); $i++)
            {
                echo '<tr align=center>';
                for($j = 0; $j < $matrixes[$idx]->getColumns(); $j++)
                {
                    echo '<td width=70px>'.round($tempMatrix[$i][$j], ROUND).'</td>';
                }
                echo '</tr>';
            }
            echo '</table>';

            echo '<p class="mx-3 mb-0" style="display: inline-block;"> >>> </p>';


            // print eigen vector
            $tempEigenVector = getEigenVector($matrixes[$idx])->toArray();
            echo '<table border=1 style="display: inline-block;">';
            echo '<tr align=center><td colspan=100><i>Eigen Vector '.$idx.'</i></td></tr>';
            for($i = 0; $i < count($tempEigenVector); $i++)
            {
                echo '<tr align=center><td width=120px>'.round($tempEigenVector[$i][0], ROUND).'</td></tr>';
            }
            echo '</table>';

            echo '</div>';
        }

        if($isDebug && $idx > 1)
        {
            $eigen1 = $eigenVector->toArray();
            $eigen2 = getEigenVector($matrixes[$idx])->toArray();
            $delta = $deltaEigenVector->toArray();

            echo '<div class="mb-5">';
            echo '<b><i>> Delta Eigen Vector</i></b><br>';

            echo '<div style="display: inline-block;">';
            echo '<table border=1 width=150px>';
            echo '<tr align=center><td><i>Eigen Vector '.($idx-1).'</i></td></tr>';
            for($i = 0; $i < count($eigen1); $i++)
            {
                echo '<tr align=center><td width=50px>'.round($eigen1[$i][0], ROUND).'</td></tr>';
            }
            echo '</table></div>';

            echo '<div style="display: inline-block;">';
            echo '<table border=1" width=150px>';
            echo '<tr align=center><td><i>Eigen Vector '.($idx).'</i></td></tr>';
            for($i = 0; $i < count($eigen2); $i++)
            {
                echo '<tr align=center><td width=50px>'.round($eigen2[$i][0], ROUND).'</td></tr>';
            }
            echo '</table></div>';

            echo '<div style="display: inline-block;">';
            echo '<table border=1" width=150px>';
            echo '<tr align=center><td><i>Delta Eigen Vector</i></td></tr>';
            for($i = 0; $i < count($delta); $i++)
            {
                echo '<tr align=center><td width=50px>'.round($delta[$i][0], ROUND).'</td></tr>';
            }
            echo '</table></div>';

            echo '</div>';
        }
    }
    while(!isDeltaEigenValid($deltaEigenVector));

    if($isDebug)
    {
        $final = getEigenVector($matrixes[$idx])->toArray();

        echo '<div>';
        echo '<b>Iterations Result :</b> STOPPED<br>';
        echo '<b>Reason :</b> each <u>delta eigen vector value</u> already LESS THAN or EQUAL to <u>threshold</u> (<i>'.THRESHOLD.'</i>)<br>';
        echo '> VALID EIGEN VECTOR <i>ACQUIRED</i>';
        echo '<br>';

        echo '<div class="mt-3">';
        echo '<table border=1>';
        echo '<tr align=center><td><i>Valid Eigen Vector</i></td></tr>';
        for($i = 0; $i < count($final); $i++)
        {
            echo '<tr align=center><td width=150px>'.round($final[$i][0], ROUND).'</td></tr>';
        }
        echo '</table></div>';

        echo '</div>';



        echo '</div><br>'; // title
    }

    $eigenVector = getEigenVector($matrixes[$idx]);
    if($isDebug) $consistent = isEigenConsistent($matrix, $eigenVector, TRUE);
    else $consistent = isEigenConsistent($matrix, $eigenVector);
    
    if($consistent) return $eigenVector;
    else return null;
}

function getDeltaEigenVector(Matrix $eigen1, Matrix $eigen2)
{
    $eigen = [];
    $eigen1 = $eigen1->toArray();
    $eigen2 = $eigen2->toArray();

    for($i = 0; $i < count($eigen1); $i++)
    {
        $eigen[$i][0] = abs($eigen1[$i][0] - $eigen2[$i][0]);
    }

    return new Matrix($eigen);
}

function isDeltaEigenValid(Matrix $eigenMatrix)
{
    foreach($eigenMatrix->getColumnValues(0) as $eigenValue)
    {
        if($eigenValue > THRESHOLD) return false;
    }

    return true;
}


// -------- //
function isEigenConsistent(Matrix $matrix, Matrix $eigen, bool $isDebug = FALSE)
{
    $RIs = [
        '1' => 0, '2' => 0,
        '3' => 0.58, '4' => 0.9,
        '5' => 1.12, '6' => 1.24,
        '7' => 1.32, '8' => 1.41,
        '9' => 1.45, '10' => 1.49,
    ];

    $n = $matrix->getColumns();
    $RI = $RIs[$n];
    
    if($isDebug) 
    {
        echo '<div>';
        echo '<h4>4. AHP CONSISTENCY CHECK</h4><br>';
        $lambdaMax = getLambdaMax($matrix, $eigen, TRUE);
    }
    else $lambdaMax = getLambdaMax($matrix, $eigen);
    $CI = ($lambdaMax - $n) / ($n - 1);
    $CR = $CI / $RI;

    if($isDebug)
    {
        echo '<div><h5><i>Consistency Ratio</i></h5>';
        echo '<table border=1>';
        echo '<tr><td width=240px><i>Random Consistency Index</i></td><td align=center width=120>'.round($RI, ROUND).'</td></tr>';
        echo '<tr><td><i>Consistency Index</i></td><td align=center>'.round($CI, ROUND).'</td></tr>';
        echo '<tr><td><i>Consistency Ratio</i></td><td align=center>'.round($CR, ROUND).'</td></tr>';
        echo '</table></div><br>';

        echo '</div>';
    }

    if(abs($CR) < 0.10) 
    {
        if($isDebug)
        {
            echo '<b>AHP Consistency Check Result :</b> inconsistent values still CAN be used<br>';
            echo '<b>Reason :</b> <u>consistency ratio</u> LESS THAN 10% (<i>CR = '.round($CR*100, 2).'%</i>)<br>';
            echo '> VALUE RECONFIGURATION <i>RECOMMENDED</i>';
        }
        return true;
    }
    else 
    {
        if($isDebug)
        {
            echo '<b>AHP Consistency Check Result :</b> inconsistent values CANNOT be used<br>';
            echo '<b>Reason :</b> consistency ratio GREATER THAN or EQUAL to 10% (<i>CR = '.round($CR*100, 2).'%</i>)<br>';
            echo '> VALUE RECONFIGURATION <i><u>NEEDED</u></i>';
        }
        return false;
    }
}

function getLambdaMax(Matrix $matrix, Matrix $eigen, bool $isDebug = FALSE)
{
    $lambdaMax = 0.0;

    if($isDebug)
    {
        echo '<div class="mb-2"><h5><i>Lambda Max</i></h5>';
        echo '<table border=1>';
        echo '<tr align=center>
                <td width=120px><i>Sum of Column</i></td>
                <td width=120px><i>Eigen Value</i></td>
                <td width=120px><i>Sum * Eigen</i></td>
              </tr>';
    }
    for($i = 0; $i < $matrix->getRows(); $i++)
    {
        $sumColumn = 0.0;
        foreach($matrix->getColumnValues($i) as $colValue)
        {
            $sumColumn += $colValue;
        }

        $lambdaMax += $sumColumn * $eigen->toArray()[$i][0];

        if ($isDebug)
        {
            echo '<tr align=center>';
            echo '<td>'.round($sumColumn, ROUND).'</td>';
            echo '<td>'.round($eigen->toArray()[$i][0], ROUND).'</td>';
            echo '<td>'.round($sumColumn * $eigen->toArray()[$i][0], ROUND).'</td>';
            echo '</tr>';
        }
    }

    if($isDebug)
    {
        echo '<tr><td colspan=3><span></span></td></tr>';
        echo '<tr align=center height=40px><td colspan=2><i>Lambda Max<i></td><td><i>'.round($lambdaMax, ROUND).'</i></td></tr>';
        echo '</table></div><br>';
    }

    return $lambdaMax;
}

?>