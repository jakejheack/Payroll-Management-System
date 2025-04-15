<table>
    <tr>
        <td colspan="5">*<td>
    </tr>
    <tr>
        <td>
        <table border="1" cellspacing="0" cellspadding="0" width="100%">
            <thead>
                <tr>
                    <th style="border: none"><img src="img/COH.png" alt="" style="width: 70px"></th>
                    <th style="border: none" colspan="3">COH Enterprises, Inc.
                    <br>
                    <b>Panday St., Goa, Camarines Sur</b>
                    <br>
                    <b>Period: '.date('M d, Y', strtotime($_SESSION['dtr_From'])).' to '.date('M d, Y', strtotime($_SESSION['dtr_To'])).'</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">Employee Name:</td>
                    <td colspan="3">'.$emp_name.'</td>
                </tr>
                <tr>
                    <td colspan="2">Designation:</td>
                    <td colspan="3">'.$position.'</td>
                </tr>
                <tr>
                    <th colspan="3">Earnings</th>
                    <th colspan="2">Deductions</th>
                </tr>
                <tr>
                    <td style="width: 25%">Particulars</td>
                    <td style="width: 10%">Hrs.</td>
                    <td style="width: 20%">Pay</td>
                    <td style="width: 25%">Particulars</td>
                    <td style="width: 20%">Amount</td>
                </tr>
                <tr>
                    <td>Reg. Days</td>
                    <td>'.round($total_hrs,2).'</td>
                    <td>'.number_format($basic_pay,2).'</td>
                    <td>A/R</td>
                    <td>'.$ar.'</td>
                </tr>
                <tr>
                    <td>Overtime</td>
                    <td>'.round($total_ot,2).'</td>
                    <td>'.number_format($ot_pay,2).'</td>
                    <td>SSS Contr.</td>
                    <td>'.$sss.'</td>
                </tr>
                <tr>
                    <td>Reg. Holiday</td>
                    <td>'.round($reg_hol_hrs,2).'</td>
                    <td>'.number_format($reg_hol_pay,2).'</td>
                    <td>PAG-IBIG</td>
                    <td>'.$pagibig.'</td>
                </tr>
                <tr>
                    <td>Reg.Hol. O.T.</td>
                    <td>'.round($reg_hol_hrs2,2).'</td>
                    <td>'.number_format($reg_hol_ot_pay,2).'</td>
                    <td>Philhealth</td>
                    <td>'.$philhealth.'</td>
                </tr>
                <tr>
                    <td>Spe. Non-Wor. Hol.</td>
                    <td>'.round($spe_hol_hrs,2).'</td>
                    <td>'.number_format($spe_hol_pay,2).'</td>
                    <td>ESF</td>
                    <td>'.$esf.'</td>
                </tr>
                <tr>
                    <td>S.H. Overtime</td>
                    <td>'.round($spe_hol_hrs2,2).'</td>
                    <td>'.number_format($spe_hol_ot_pay,2).'</td>
                    <td>Salary Loan</td>
                    <td>'.$salary_loan.'</td>
                </tr>
                <tr>
                    <td>Day-Off w/ Work</td>
                    <td>'.round($off_hrs,2).'</td>
                    <td>'.number_format($off_pay,2).'</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>D.W. Overtime</td>
                    <td>'.round($off_hrs2,2).'</td>
                    <td>'.number_format($off_ot_pay,2).'</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Total Earnings</th>
                    <th colspan="2">'.number_format($gross,2).'</th>
                    <th>Total Ded.</th>
                    <th>'.number_format($deductions,2).'</th>
                </tr>
                <tr>
                    <th colspan="3">Net Pay</th>
                    <th colspan="2">'.number_format($net_pay,2).'</th>
                </tr>
            </tbody>
        </table>
        </td>
    </tr>
</table>