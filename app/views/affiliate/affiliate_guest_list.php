<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>

<!-- project details -->
         <div class="clsAffiliateTable">
                            <table width="650" cellspacing="1" cellpadding="2">
                              <tbody>
                                <tr>
                                  <td width="148" align="center" class="dt">&nbsp;</td>
								  <td width="248" class="dt">Refer aBuyer earn...</td>
                                  <td width="248" align="center" class="dt">Refer a Programmer earn...</td>
                                </tr>
                                <tr>
                                  <td class="dt1 dt0"><b>Regular Project</b></td>
                                  <td class="dt1"> <b>$2-$2.40</b> plus <b><?php if(isset($affiliate['buyer_affiliate_fee'])) echo $affiliate['buyer_affiliate_fee']; ?>%</b> of fee we charge their selected Programmer (Min: $<?php if(isset($affiliate['buyer_min_amount'])) echo $affiliate['buyer_min_amount']; ?>)<br /><br />Minimum payout per project: <b>$<?php if(isset($affiliate['buyer_min_payout'])) echo $affiliate['buyer_min_payout']; ?>.00</b><br />Maximum payout per project: <b><?php if(isset($affiliate['buyer_max_payout'])) echo $affiliate['buyer_max_payout']; ?></b></td>
                                  <td class="dt1"><b><?php if(isset($affiliate['programmer_affiliate_fee'])) echo $affiliate['programmer_affiliate_fee']; ?>%</b> of fee charged to Programmer (Min: $<?php if(isset($affiliate['programmer_min_amount'])) echo $affiliate['programmer_min_amount']; ?>), plus $0.50-$0.60 (<?php if(isset($affiliate['programmer_affiliate_fee'])) echo $affiliate['programmer_affiliate_fee']; ?>% of Buyer's fee).<br /><br />Minimum payout per project: <b>$<?php if(isset($affiliate['programmer_min_payout'])) echo $affiliate['programmer_min_payout']; ?>.00</b><br />Maximum payout per project: <b><?php if(isset($affiliate['programmer_max_payout'])) echo $affiliate['programmer_max_payout']; ?></b></td>
                                </tr>
                                <tr>
                                  <td class="dt2 dt0"><b>Featured Project (?)</b></td>								
                                  <td class="dt2"><b><?php if(isset($affiliate['buyer_project_fee'])) echo $affiliate['buyer_project_fee']; ?>%</b> of Featured project fee ($10). Plus <?php if(isset($affiliate['buyer_project_fee'])) echo $affiliate['buyer_project_fee']; ?>% of fee charged to selected Programmer.</td>
                                  <td  class="dt2"><b><?php if(isset($affiliate['programmer_project_fee'])) echo $affiliate['programmer_project_fee']; ?>%</b> of Featured project fee ($2.50). Plus <?php if(isset($affiliate['programmer_project_fee'])) echo $affiliate['programmer_project_fee']; ?>% of fee charged to selected Programmer.</td>
                                </tr>

                              </tbody>
                            </table>
      </div>
         <!-- end of project details -->

<?php $this->load->view('footer'); ?>