<table width="100%">
        <tr>
            <td colspan="4">*<td>
        </tr>
        <tr>
            <td style="width: 49%" >
                <table border="1" cellspacing="0" cellspadding="0" width="100%">
                    <thead>
                        <tr>
                            <th style="border: none"><img src="img/COH.jpg" alt="" style="width: 70px"></th>
                            <th style="border: none" colspan="3"><h3>COH Enterprises, Inc.
                            <br>
                            <b>Panday St., Goa, Camarines Sur</b>
                            <br>
                            <b>Period: '.date('M d, Y', strtotime($from)).' to '.date('M d, Y', strtotime($to)).'</b>
                            </h3></th>
                        </tr>
                        <tr>
                            <td colspan=""><h3>Name:</h3></td>
                            <td colspan="3"><h3>'.$row_payroll_h['emp_name'].'</h3></td>
                        </tr>
                        <tr>
                            <td colspan=""><h3>Position:</h3></td>
                            <td colspan="3"><b>'.$position.'</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th colspan="4">Earnings</th>
                        </tr>
                        <tr>
                            <td style="width: 25%" colspan="2">Particulars</td>
                            <td style="width: 10%">Hrs.</td>
                            <td style="width: 20%">Pay</td>
                        </tr>
                        <tr>
                            <td colspan="2">Reg. Hrs.</td>
                            <td>'.$row_payroll_h['reg_hrs'].'</td>
                            <td>'.$row_payroll_h['reg_pay'].'</td>
                        </tr>
                        <tr>
                            <td colspan="2">O.T.</td>
                            <td>'.$row_payroll_h['ot_hrs'].'</td>
                            <td>'.$row_payroll_h['ot_pay'].'</td>
                        </tr>
                        <tr>
                            <td colspan="2">Reg. Holiday</td>
                            <td>'.$row_payroll_h['reg_hol_hrs'].'</td>
                            <td>'.$row_payroll_h['reg_hol_pay'].'</td>
                        </tr>
                        <tr>
                            <td colspan="2">Special Holiday</td>
                            <td>'.$row_payroll_h['spe_hol_hrs'].'</td>
                            <td>'.$row_payroll_h['spe_hol_pay'].'</td>
                        </tr>
                        <tr>
                            <td colspan="2">Work on Dayoff</td>
                            <td>'.$row_payroll_h['off_hrs'].'</td>
                            <td>'.$row_payroll_h['off_pay'].'</td>
                        </tr>
                        <tr>
                            <td colspan="2">Total Worked Hrs/Amount</td>
                            <td>'.$row_payroll_h['reg_hrs'] + $row_payroll_h['add_hrs'].'</td>
                            <td>'.$row_payroll_h['reg_pay'] + $row_payroll_h['add_pay'].'</td>
                        </tr>
                        <tr>
                            <th colspan="2">Total Earnings</th>
                            <th></th>
                            <th colspan="">'.$row_payroll_h['gross'].'</th>
                        </tr>
                        <tr>
                            <th colspan="4">Deductions</th>
                        </tr>
                                $html .= '<tr><td>SSS Contr.</td>
                                        <td>'.$row_payroll_h['sss'].'</td>';
                                $html .= '<td>ESF</td>
                                        <td>'.$row_payroll_h['esf'].'</td></tr>';

                                $html .= '<tr><td>HDMF</td>
                                        <td>'.$row_payroll_h['pagibig'].'</td>';
                                $html .= '<td>A/R</td>
                                        <td>'.$row_payroll_h['ar'].'</td></tr>';
                                $html .= '<tr><td>PHILHEALTH</td>
                                        <td>'.$row_payroll_h['philhealth'].'</td>';
                                $html .= '<td>Shortages</td>
                                        <td>'.$row_payroll_h['shortages'].'</td></tr>';
                                $html .= '<tr><td></td>
                                            <td></td>';
                                $html .= '<td>Salary Loan</td>
                                        <td>'.$row_payroll_h['salary_loan'].'</td></tr>';
                                    
$html .=        '<tr>
                            <th colspan="2">Total Ded.</th>
                            <th colspan="2">'.$row_payroll_h['deductions'].'</th>
                        </tr>
                        <tr>
                            <th colspan="2"><h3>Net Pay</h3></th>
                            <th colspan="2"><h3>'.$row_payroll_h['net_pay'].'</h3></th>
                        </tr>
                        <tr>
                            <td colspan="4">
                                I acknowledge to have recieved the amount of and have no further claims for services rendered.
                                <br>
                                <span>Employee`s Signature</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td style="width: 2%"></td>
            <td style="width: 49%">
            <table border="1" cellspacing="0" cellspadding="0" width="100%">
                    <thead>
                        <tr>
                            <th style="border: none"><img src="img/COH.jpg" alt="" style="width: 70px"></th>
                            <th style="border: none" colspan="3"><h3>COH Enterprises, Inc.
                            <br>
                            <b>Panday St., Goa, Camarines Sur</b>
                            <br>
                            <b>Period: '.date('M d, Y', strtotime($from)).' to '.date('M d, Y', strtotime($to)).'</b>
                            </h3></th>
                        </tr>
                        <tr>
                            <td colspan=""><h3>Name:</h3></td>
                            <td colspan="3"><h3>'.$row_payroll_h['emp_name'].'</h3></td>
                        </tr>
                        <tr>
                            <td colspan=""><h3>Position:</h3></td>
                            <td colspan="3"><b>'.$position.'</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th colspan="4">Earnings</th>
                        </tr>
                        <tr>
                            <td style="width: 25%" colspan="2">Particulars</td>
                            <td style="width: 10%">Hrs.</td>
                            <td style="width: 20%">Pay</td>
                        </tr>
                        <tr>
                            <td colspan="2">Reg. Hrs.</td>
                            <td>'.$row_payroll_h['reg_hrs'].'</td>
                            <td>'.$row_payroll_h['reg_pay'].'</td>
                        </tr>
                        <tr>
                            <td colspan="2">O.T.</td>
                            <td>'.$row_payroll_h['ot_hrs'].'</td>
                            <td>'.$row_payroll_h['ot_pay'].'</td>
                        </tr>
                        <tr>
                            <td colspan="2">Reg. Holiday</td>
                            <td>'.$row_payroll_h['reg_hol_hrs'].'</td>
                            <td>'.$row_payroll_h['reg_hol_pay'].'</td>
                        </tr>
                        <tr>
                            <td colspan="2">Special Holiday</td>
                            <td>'.$row_payroll_h['spe_hol_hrs'].'</td>
                            <td>'.$row_payroll_h['spe_hol_pay'].'</td>
                        </tr>
                        <tr>
                            <td colspan="2">Work on Dayoff</td>
                            <td>'.$row_payroll_h['off_hrs'].'</td>
                            <td>'.$row_payroll_h['off_pay'].'</td>
                        </tr>
                        <tr>
                            <td colspan="2">Total Worked Hrs/Amount</td>
                            <td>'.$row_payroll_h['reg_hrs'] + $row_payroll_h['add_hrs'].'</td>
                            <td>'.$row_payroll_h['reg_pay'] + $row_payroll_h['add_pay'].'</td>
                        </tr>
                        <tr>
                            <th colspan="2">Total Earnings</th>
                            <th></th>
                            <th colspan="">'.$row_payroll_h['gross'].'</th>
                        </tr>
                        <tr>
                            <th colspan="4">Deductions</th>
                        </tr>
                                $html .= '<tr><td>SSS Contr.</td>
                                        <td>'.$row_payroll_h['sss'].'</td>';
                                $html .= '<td>ESF</td>
                                        <td>'.$row_payroll_h['esf'].'</td></tr>';

                                $html .= '<tr><td>HDMF</td>
                                        <td>'.$row_payroll_h['pagibig'].'</td>';
                                $html .= '<td>A/R</td>
                                        <td>'.$row_payroll_h['ar'].'</td></tr>';
                                $html .= '<tr><td>PHILHEALTH</td>
                                        <td>'.$row_payroll_h['philhealth'].'</td>';
                                $html .= '<td>Shortages</td>
                                        <td>'.$row_payroll_h['shortages'].'</td></tr>';
                                $html .= '<tr><td></td>
                                            <td></td>';
                                $html .= '<td>Salary Loan</td>
                                        <td>'.$row_payroll_h['salary_loan'].'</td></tr>';
                                    
$html .=        '<tr>
                            <th colspan="2">Total Ded.</th>
                            <th colspan="2">'.$row_payroll_h['deductions'].'</th>
                        </tr>
                        <tr>
                            <th colspan="2"><h3>Net Pay</h3></th>
                            <th colspan="2"><h3>'.$row_payroll_h['net_pay'].'</h3></th>
                        </tr>
                        <tr>
                            <td colspan="4">
                                I acknowledge to have recieved the amount of and have no further claims for services rendered.
                                <br>
                                <span>Employee`s Signature</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
        </td>
        </tr >
            </table>

            <style>
                .a{
                    text-align: right;
                }
            </style>