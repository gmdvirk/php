<!-- Main content -->
<style>
    .limitInfo  {
        color: blue
    }
    .flex {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
    }
    .mgt {
        margin-left: 22%;
    }
</style>
<section class="content col-sm-6">	
	<?php if(!empty($this->session->flashdata('error'))) : ?>
    <div class="alert alert-error alert-dismissible" >
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> Error!</h4>
        <p><?=$this->session->flashdata('error');?>
    </div>
    <?php endif; ?>
	<div class="box box-default">
		<div class="box-header with-border flex">
			<h3 class="box-title"><?=$pageTitle;?></h3>
            <!-- <input type="button" name="" value="Copy Manager Settings" class="btn btn-warning mgt"> -->
            <?php if(isset($copyUrl)){?>
                <a href="<?=base_url($copyUrl);?>" class="btn btn-warning pull-right mgt">Copy Manager Settings</a>
            <?php } ?>
		</div>
		<div class="box-body">
            <?php 
            	if($json) {
				    $ABHR_NO_DOOR = isset($json['AbhrNoDr']) ? $json['AbhrNoDr'] : 0;
					$DOOR_NO_ABHR_BEEP = isset($json['DrNoAbhrBp']) ? $json['DrNoAbhrBp'] : 0;
					$MASK_SETTING = isset($json['MskSett']) ? $json['MskSett'] : 0;
				    $DEBUG_BMODE = isset($json['DbgMod']) ? $json['DbgMod'] : 1;
				    $BEEP_STG = isset($json['BpStg']) ? $json['BpStg'] : 1;
				    $DOOR_RSSI_THRESHOLD = isset($json['DrRssThrs']) ? $json['DrRssThrs'] : 0;
                    $ABHR_RSSI_THRESHOLD = isset($json['AbhrRssThrs']) ? $json['AbhrRssThrs'] : 0;
                    $SOAP_RSSI_THRESHOLD = isset($json['SpRssThrs']) ? $json['SpRssThrs'] : 0;
                    $BADGE_RSSI_THRESHOLD = isset($json['BgRssThrs']) ? $json['BgRssThrs'] : 20;
                    $BED_RSSI_THRESHOLD = isset($json['BdRssThrs']) ? $json['BdRssThrs'] : 0;
                    $PROV_RSSI_THRESHOLD = isset($json['PrvRssThrs']) ? $json['PrvRssThrs'] : 0;
                    $COMM_RSSI_THRESHOLD = isset($json['CmRssThrs']) ? $json['CmRssThrs'] : 0;
                    $SOAP_QUALIFY_PERIOD = isset($json['SpQualPer']) ? $json['SpQualPer'] : 0;
                    $BED_ZONE_PERIOD = isset($json['BdZnPer']) ? $json['BdZnPer'] : 0;
                    $BEEP_ON_RSSIPASS = isset($json['BpOnRssP']) ? $json['BpOnRssP'] : 1;
                    $BEEP_ON_RSSIFAIL = isset($json['BpOnRssF']) ? $json['BpOnRssF'] : 1;
                    $BED_HYST_PERIOD = isset($json['BdHystPer']) ? $json['BdHystPer'] : 1;
                    $SOAP_HYST_PERIOD = isset($json['SpLeavePer']) ? $json['SpLeavePer'] : 1;
                    $COMPLIANT_BEEP = isset($json['CompliantBeep']) ? $json['CompliantBeep'] : 1;
                    $HANDWASH_SUC_BEEP = isset($json['HandWashSucBeep']) ? $json['HandWashSucBeep'] : 1;
                    $HANDWASH_FAIL_BEEP = isset($json['HandWashFailBeep']) ? $json['HandWashFailBeep'] : 1;
                    if(isset($manager)):
                        $zn = isset($json['zn']) ? $json['zn'] : -11.00;
                        $st = isset($json['st']) ? $json['st'] : 0.00;
                        $sp = isset($json['sp']) ? $json['sp'] : 0.00;
                    endif;
				}else{
                    $ABHR_NO_DOOR = NULL;
					$DOOR_NO_ABHR_BEEP = NULL;
					$MASK_SETTING = NULL;
				    $DEBUG_BMODE = NULL;
				    $BEEP_STG = NULL;
				    $DOOR_RSSI_THRESHOLD = NULL;
                    $ABHR_RSSI_THRESHOLD = NULL;
                    $SOAP_RSSI_THRESHOLD = NULL;
                    $BADGE_RSSI_THRESHOLD = NULL;
                    $BED_RSSI_THRESHOLD = NULL;
                    $PROV_RSSI_THRESHOLD = NULL;
                    $COMM_RSSI_THRESHOLD = NULL;
                    $SOAP_QUALIFY_PERIOD = NULL;
                    $BED_ZONE_PERIOD = NULL;
                    $BEEP_ON_RSSIPASS = NULL;
                    $BEEP_ON_RSSIFAIL = NULL;
                    $BED_HYST_PERIOD = NULL;
                    $SOAP_HYST_PERIOD =  NULL;
                    $COMPLIANT_BEEP = NULL;
                    $HANDWASH_SUC_BEEP = NULL;
                    $HANDWASH_FAIL_BEEP = NULL;

                    
                    if(isset($manager)):
                        $zn = -11.00;
                        $st = 0.00;
                        $sp = 0.00;
                    endif;
                }
                
                if(isset($json_accepted['AbhrNoDr']) && !isset($manager)) {
                    //running data
                    $ABHR_NO_DOOR_RUNNING = isset($json_accepted['AbhrNoDr']) ? $json_accepted['AbhrNoDr'] : 0;
					$DOOR_NO_ABHR_BEEP_RUNNING = isset($json_accepted['DrNoAbhrBp']) ? $json_accepted['DrNoAbhrBp'] : 0;
					$MASK_SETTING_RUNNING = isset($json_accepted['MskSett']) ? $json_accepted['MskSett'] : 0;
				    $DEBUG_BMODE_RUNNING = empty($json_accepted['DbgMod']) ? 'OFF' : 'ON';
				    $BEEP_STG_RUNNING = empty($json_accepted['BpStg']) ? 'OFF' : 'ON';
				    $DOOR_RSSI_THRESHOLD_RUNNING = isset($json_accepted['DrRssThrs']) ? $json_accepted['DrRssThrs'] : 0;
                    $ABHR_RSSI_THRESHOLD_RUNNING = isset($json_accepted['AbhrRssThrs']) ? $json_accepted['AbhrRssThrs'] : 0;
                    $SOAP_RSSI_THRESHOLD_RUNNING = isset($json_accepted['SpRssThrs']) ? $json_accepted['SpRssThrs'] : 0;
                    $BADGE_RSSI_THRESHOLD_RUNNING = isset($json_accepted['BgRssThrs']) ? $json_accepted['BgRssThrs'] : 20;
                    $BED_RSSI_THRESHOLD_RUNNING = isset($json_accepted['BdRssThrs']) ? $json_accepted['BdRssThrs'] : 0;
                    $PROV_RSSI_THRESHOLD_RUNNING = isset($json_accepted['PrvRssThrs']) ? $json_accepted['PrvRssThrs'] : 0;
                    $COMM_RSSI_THRESHOLD_RUNNING = isset($json_accepted['CmRssThrs']) ? $json_accepted['CmRssThrs'] : 0;
                    $SOAP_QUALIFY_PERIOD_RUNNING = isset($json_accepted['SpQualPer']) ? $json_accepted['SpQualPer'] : 0;
                    $BED_ZONE_PERIOD_RUNNING = isset($json_accepted['BdZnPer']) ? $json_accepted['BdZnPer'] : 0;
                    $BEEP_ON_RSSIPASS_RUNNING = empty($json_accepted['BpOnRssP']) ? 'OFF' : 'ON';
                    $BEEP_ON_RSSIFAIL_RUNNING = empty($json_accepted['BpOnRssF']) ? 'OFF' : 'ON';
                    $BED_HYST_PERIOD_RUNNING = isset($json_accepted['BdHystPer']) ? $json_accepted['BdHystPer'] : 1;
                    $SOAP_HYST_PERIOD_RUNNING = isset($json_accepted['SpLeavePer']) ? $json_accepted['SpLeavePer'] : 1;
                    $COMPLIANT_BEEP_RUNNING = empty($json_accepted['CompliantBeep']) ? 'OFF' : 'ON';
                    $HANDWASH_SUC_BEEP_RUNNING = empty($json_accepted['HandWashSucBeep']) ? 'OFF' : 'ON';
                    $HANDWASH_FAIL_BEEP_RUNNING = empty($json_accepted['HandWashFailBeep']) ? 'OFF' : 'ON';
                    
				}else{
                    //running data
                    $ABHR_NO_DOOR_RUNNING = NULL;
					$DOOR_NO_ABHR_BEEP_RUNNING = NULL;
					$MASK_SETTING_RUNNING = NULL;
				    $DEBUG_BMODE_RUNNING = NULL;
				    $BEEP_STG_RUNNING = NULL;
				    $DOOR_RSSI_THRESHOLD_RUNNING = NULL;
                    $ABHR_RSSI_THRESHOLD_RUNNING = NULL;
                    $SOAP_RSSI_THRESHOLD_RUNNING = NULL;
                    $BADGE_RSSI_THRESHOLD_RUNNING = NULL;
                    $BED_RSSI_THRESHOLD_RUNNING = NULL;
                    $PROV_RSSI_THRESHOLD_RUNNING = NULL;
                    $COMM_RSSI_THRESHOLD_RUNNING = NULL;
                    $SOAP_QUALIFY_PERIOD_RUNNING = NULL;
                    $BED_ZONE_PERIOD_RUNNING = NULL;
                    $BEEP_ON_RSSIPASS_RUNNING = NULL;
                    $BEEP_ON_RSSIFAIL_RUNNING = NULL;
                    $BED_HYST_PERIOD_RUNNING = NULL;
                    $SOAP_HYST_PERIOD_RUNNING = NULL;
                    $COMPLIANT_BEEP_RUNNING = NULL;
                    $HANDWASH_SUC_BEEP_RUNNING = NULL;
                    $HANDWASH_FAIL_BEEP_RUNNING = NULL;
                }
                if(isset($manager_json)){
                    $m_ABHR_NO_DOOR_RUNNING = isset($manager_json['AbhrNoDr']) ? $manager_json['AbhrNoDr'] : 0;
					$m_DOOR_NO_ABHR_BEEP_RUNNING = isset($manager_json['DrNoAbhrBp']) ? $manager_json['DrNoAbhrBp'] : 0;
					$m_MASK_SETTING_RUNNING = isset($manager_json['MskSett']) ? $manager_json['MskSett'] : 0;
				    $m_DEBUG_BMODE_RUNNING = empty($manager_json['DbgMod']) ? 'OFF' : 'ON';
				    $m_BEEP_STG_RUNNING = empty($manager_json['BpStg']) ? 'OFF' : 'ON';
				    $m_DOOR_RSSI_THRESHOLD_RUNNING = isset($manager_json['DrRssThrs']) ? $manager_json['DrRssThrs'] : 0;
                    $m_ABHR_RSSI_THRESHOLD_RUNNING = isset($manager_json['AbhrRssThrs']) ? $manager_json['AbhrRssThrs'] : 0;
                    $m_SOAP_RSSI_THRESHOLD_RUNNING = isset($manager_json['SpRssThrs']) ? $manager_json['SpRssThrs'] : 0;
                    $m_BADGE_RSSI_THRESHOLD_RUNNING = isset($manager_json['BgRssThrs']) ? $manager_json['BgRssThrs'] : 20;
                    $m_BED_RSSI_THRESHOLD_RUNNING = isset($manager_json['BdRssThrs']) ? $manager_json['BdRssThrs'] : 0;
                    $m_PROV_RSSI_THRESHOLD_RUNNING = isset($manager_json['PrvRssThrs']) ? $manager_json['PrvRssThrs'] : 0;
                    $m_COMM_RSSI_THRESHOLD_RUNNING = isset($manager_json['CmRssThrs']) ? $manager_json['CmRssThrs'] : 0;
                    $m_SOAP_QUALIFY_PERIOD_RUNNING = isset($manager_json['SpQualPer']) ? $manager_json['SpQualPer'] : 0;
                    $m_BED_ZONE_PERIOD_RUNNING = isset($manager_json['BdZnPer']) ? $manager_json['BdZnPer'] : 0;
                    $m_BEEP_ON_RSSIPASS_RUNNING = empty($manager_json['BpOnRssP']) ? 'OFF' : 'ON';
                    $m_BEEP_ON_RSSIFAIL_RUNNING = empty($manager_json['BpOnRssF']) ? 'OFF' : 'ON';
                    $m_BED_HYST_PERIOD_RUNNING = isset($manager_json['BdHystPer']) ? $manager_json['BdHystPer'] : 1;
                    $m_SOAP_HYST_PERIOD_RUNNING = isset($manager_json['SpLeavePer']) ? $manager_json['SpLeavePer'] : 1;
                    $m_COMPLIANT_BEEP_RUNNING = empty($manager_json['CompliantBeep']) ? 'OFF' : 'ON';
                    $m_HANDWASH_SUC_BEEP_RUNNING = empty($manager_json['HandWashSucBeep']) ? 'OFF' : 'ON';
                    $m_HANDWASH_FAIL_BEEP_RUNNING = empty($manager_json['HandWashFailBeep']) ? 'OFF' : 'ON';
                    
				}else{
                    //running data
                    $m_ABHR_NO_DOOR_RUNNING = NULL;
					$m_DOOR_NO_ABHR_BEEP_RUNNING = NULL;
					$m_MASK_SETTING_RUNNING = NULL;
				    $m_DEBUG_BMODE_RUNNING = NULL;
				    $m_BEEP_STG_RUNNING = NULL;
				    $m_DOOR_RSSI_THRESHOLD_RUNNING = NULL;
                    $m_ABHR_RSSI_THRESHOLD_RUNNING = NULL;
                    $m_SOAP_RSSI_THRESHOLD_RUNNING = NULL;
                    $m_BADGE_RSSI_THRESHOLD_RUNNING = NULL;
                    $m_BED_RSSI_THRESHOLD_RUNNING = NULL;
                    $m_PROV_RSSI_THRESHOLD_RUNNING = NULL;
                    $m_COMM_RSSI_THRESHOLD_RUNNING = NULL;
                    $m_SOAP_QUALIFY_PERIOD_RUNNING = NULL;
                    $m_BED_ZONE_PERIOD_RUNNING = NULL;
                    $m_BEEP_ON_RSSIPASS_RUNNING = NULL;
                    $m_BEEP_ON_RSSIFAIL_RUNNING = NULL;
                    $m_BED_HYST_PERIOD_RUNNING = NULL;
                    $m_SOAP_HYST_PERIOD_RUNNING = NULL;
                    $m_COMPLIANT_BEEP_RUNNING = NULL;
                    $m_HANDWASH_SUC_BEEP_RUNNING = NULL;
                    $m_HANDWASH_FAIL_BEEP_RUNNING = NULL;
                }

			    echo form_open($action); ?>
                <div class="box-body row">
                    <div class="col-md-5"><span></span></div>
                    <div class="col-md-3"><span>Desired</span></div>
                    <?php if(!isset($manager)) : ?>
                        <div class="col-md-2"><span>Running</span></div>
                    <?php endif; ?>
                    <?php if(isset($manager_json)) { ?>
                    <div class="col-md-2"><span>Facility</span></div>
                    <?php } ?>

                </div>
			    <div class="box-body edit_json_body_wrapper">
                <div class="form-group row">
					<label for="DrRssThrs" class="col-md-5">Door RSSI Threshold <span class="limitInfo">(0-31)</span></label>
					<input class="form-control col-md-3"  value="<?= $DOOR_RSSI_THRESHOLD;?>"  type="number" id="DrRssThrs" name="DrRssThrs" min="0" max="31">
                    <span class="col-md-2" style="text-align:center;"><?php echo $DOOR_RSSI_THRESHOLD_RUNNING ?></span>
                    <span class="col-md-2" style="text-align:center;"><?php echo $m_DOOR_RSSI_THRESHOLD_RUNNING ?></span>
                </div>
                <div class="form-group row">
					<label for="AbhrRssThrs" class="col-md-5">ABHR RSSI Threshold <span class="limitInfo">(0-31)</span></label>
					<input class="form-control col-md-3"  value="<?= $ABHR_RSSI_THRESHOLD;?>"  type="number" id="AbhrRssThrs" name="AbhrRssThrs" min="0" max="31">
                    <span class="col-md-2" style="text-align:center;"> <?php echo $ABHR_RSSI_THRESHOLD_RUNNING ?></span>
                    <span class="col-md-2" style="text-align:center;"><?php echo $m_ABHR_RSSI_THRESHOLD_RUNNING ?></span>
                </div>
                <div class="form-group row">
					<label for="SpRssThrs" class="col-md-5">Soap RSSI Threshold <span class="limitInfo">(0-31)</span></label>
					<input class="form-control col-md-3"  value="<?= $SOAP_RSSI_THRESHOLD;?>"  type="number" id="SpRssThrs" name="SpRssThrs" min="0" max="31">
                    <span class="col-md-2" style="text-align:center;"><?php echo $SOAP_RSSI_THRESHOLD_RUNNING ?></span>
                    <span class="col-md-2" style="text-align:center;"><?php echo $m_SOAP_RSSI_THRESHOLD_RUNNING ?></span>
                </div>
                
                <div class="form-group row">
					<label for="BdRssThrs" class="col-md-5">Bed RSSI Threshold <span class="limitInfo">(0-31)</span></label>
					<input class="form-control col-md-3" value="<?= $BED_RSSI_THRESHOLD;?>" type="number" id="BdRssThrs" name="BdRssThrs" min="0" max="31">
                    <span class="col-md-2" style="text-align:center;"><?php echo $BED_RSSI_THRESHOLD_RUNNING ?></span>
                    <span class="col-md-2" style="text-align:center;"><?php echo $m_BED_RSSI_THRESHOLD_RUNNING ?></span>
                </div>
                
                <div class="form-group row">
					<label for="CmRssThrs" class="col-md-5">Comms RSSI Threshold <span class="limitInfo">(0-31)</span></label>
					<input class="form-control col-md-3" value="<?= $COMM_RSSI_THRESHOLD;?>" type="number" id="CmRssThrs" name="CmRssThrs" min="0" max="31">
                    <span class="col-md-2" style="text-align:center;"><?php echo $COMM_RSSI_THRESHOLD_RUNNING ?></span>
                    <span class="col-md-2" style="text-align:center;"><?php echo $m_COMM_RSSI_THRESHOLD_RUNNING ?></span>
                </div>
                <div class="form-group row">
                    <label for="AbhrNoDr" class="col-md-5">Sanitized Timer <span class="limitInfo">(0-45)</span></label>
                    <input class="form-control col-md-3" value="<?= $ABHR_NO_DOOR;?>" type="number" id="AbhrNoDr" name="AbhrNoDr" min="0" max="45">
                    <span class="col-md-2" style="text-align:center;"><?php echo $ABHR_NO_DOOR_RUNNING ?></span>
                    <span class="col-md-2" style="text-align:center;"><?php echo $m_ABHR_NO_DOOR_RUNNING ?></span>
                </div>
				<div class="form-group row">
					<label for="DrNoAbhrBp" class="col-md-5">Door Timer <span class="limitInfo">(0-20)</span></label>
                    <input class="form-control col-md-3" value="<?= $DOOR_NO_ABHR_BEEP;?>" type="number" id="DrNoAbhrBp" name="DrNoAbhrBp" min="0" max="20">
                    <span class="col-md-2" style="text-align:center;"><?php echo $DOOR_NO_ABHR_BEEP_RUNNING ?></span>
                    <span class="col-md-2" style="text-align:center;"><?php echo $m_DOOR_NO_ABHR_BEEP_RUNNING ?></span>
				</div>
                <div class="form-group row">
					<label for="SpQualPer" class="col-md-5">Soap Handwashing Timer <span class="limitInfo">(0-20)</span></label>
					<input class="form-control col-md-3" value="<?= $SOAP_QUALIFY_PERIOD;?>" type="number" id="SpQualPer" name="SpQualPer" min="0" max="20">
                    <span class="col-md-2" style="text-align:center;"><?php echo $SOAP_QUALIFY_PERIOD_RUNNING ?></span>
                    <span class="col-md-2" style="text-align:center;"><?php echo $m_SOAP_QUALIFY_PERIOD_RUNNING ?></span>
                </div>
                <div class="form-group row">
					<label for="SpQualPer" class="col-md-5">Soap Zone Enter/Exit Period<span class="limitInfo">(0-20)</span></label>
					<input class="form-control col-md-3" value="<?= $SOAP_HYST_PERIOD;?>" type="number" id="SpLeavePer" name="SpLeavePer" min="0" max="20">
                    <span class="col-md-2" style="text-align:center;"><?php echo $SOAP_HYST_PERIOD_RUNNING ?></span>
                    <span class="col-md-2" style="text-align:center;"><?php echo $m_SOAP_HYST_PERIOD_RUNNING ?></span>
                </div>
                <div class="form-group row">
					<label for="BdZnPer" class="col-md-5">Bed Exit Timer <span class="limitInfo">(0-45)</span></label>
					<input class="form-control col-md-3" value="<?= $BED_ZONE_PERIOD;?>" type="number" id="BdZnPer" name="BdZnPer" min="0" max="45">
                    <span class="col-md-2" style="text-align:center;"><?php echo $BED_ZONE_PERIOD_RUNNING ?></span>
                    <span class="col-md-2" style="text-align:center;"><?php echo $m_BED_ZONE_PERIOD_RUNNING ?></span>
                </div>
                <div class="form-group row">
					<label for="BdHystPer" class="col-md-5">Bed Zone Enter/Exit Period <span class="limitInfo">(0-20)</span></label>
					<input class="form-control col-md-3" value="<?= $BED_HYST_PERIOD;?>" type="number" id="BdHystPer" name="BdHystPer" min="0" max="20">
                    <span class="col-md-2"><?php echo $BED_HYST_PERIOD_RUNNING ?></span>
                    <span class="col-md-2" style="text-align:center;"><?php echo $m_BED_HYST_PERIOD_RUNNING ?></span>
                </div>
                <div class="form-group row">
                    <label for="CompliantBeep" class="col-md-5">Compliant Beep</label>
                    <select name="CompliantBeep" id="CompliantBeep" class="form-control col-md-3" >
                        <option <?= $COMPLIANT_BEEP == 1 ? 'selected' : ''; ?> value="1">ON</option>
                        <option <?= $COMPLIANT_BEEP == 0 ? 'selected' : ''; ?> value="0">OFF</option>
                    </select>
                    <span class="col-md-2"><?php echo $COMPLIANT_BEEP_RUNNING ?></span>
                    <span class="col-md-2" style="text-align:center;"><?php echo $m_COMPLIANT_BEEP_RUNNING ?></span>
                </div>
                <div class="form-group row">
                        <label for="BpStg" class="col-md-5">Non-compliant Beep</label>
                        <select name="BpStg" id="BpStg" class="form-control col-md-3" required >
                            <option <?= $BEEP_STG == 1 ? 'selected' : ''; ?> value="1">ON</option>
                            <option <?= $BEEP_STG == 0 ? 'selected' : ''; ?> value="0">OFF</option>
                        </select>
                        <span class="col-md-2"><?php echo $BEEP_STG_RUNNING ?></span>
                        <span class="col-md-2" style="text-align:center;"><?php echo $m_BEEP_STG_RUNNING ?></span>
                    </div>
                <div class="form-group row">
                    <label for="HandWashSucBeep" class="col-md-5">Handwashing Success Beep</label>
                    <select name="HandWashSucBeep" id="HandWashSucBeep" class="form-control col-md-3" >
                        <option <?= $HANDWASH_SUC_BEEP == 1 ? 'selected' : ''; ?> value="1">ON</option>
                        <option <?= $HANDWASH_SUC_BEEP == 0 ? 'selected' : ''; ?> value="0">OFF</option>
                    </select>
                    <span class="col-md-2"><?php echo $HANDWASH_SUC_BEEP_RUNNING ?></span>
                    <span class="col-md-2" style="text-align:center;"><?php echo $m_HANDWASH_SUC_BEEP_RUNNING ?></span>
                </div>
                <div class="form-group row">
                    <label for="HandWashFailBeep" class="col-md-5">Handwashing Failure Beep</label>
                    <select name="HandWashFailBeep" id="HandWashFailBeep" class="form-control col-md-3" required >
                        <option <?= $HANDWASH_FAIL_BEEP == 1 ? 'selected' : ''; ?> value="1">ON</option>
                        <option <?= $HANDWASH_FAIL_BEEP == 0 ? 'selected' : ''; ?> value="0">OFF</option>
                    </select>
                    <span class="col-md-3"><?php echo $HANDWASH_FAIL_BEEP_RUNNING ?></span>
                    <span class="col-md-2"><?php echo $HANDWASH_SUC_BEEP_RUNNING ?></span>
                    <span class="col-md-2" style="text-align:center;"><?php echo $m_HANDWASH_SUC_BEEP_RUNNING ?></span>
                </div>
                <!-- <button class="btn btn-primary show-advance-settings">
                    Show Advanced Settings
                </button> -->
                <!-- <div class="advance-settings-fields section-hide"> -->
                    <div class="form-group row">
                        <label for="MskSett" class="col-md-5">Door Mask <span class="limitInfo">(0-20)</span></label>
                        <input class="form-control col-md-3" value="<?= $MASK_SETTING;?>" type="number" id="MskSett" name="MskSett" min="0" max="20">
                        <span class="col-md-2"><?php echo $MASK_SETTING_RUNNING ?></span>
                        <span class="col-md-2" style="text-align:center;"><?php echo $m_MASK_SETTING_RUNNING ?></span>
                    </div>
                    <div class="form-group row">
                        <label for="BgRssThrs" class="col-md-5">Badge RSSI Threshold <span class="limitInfo">(20-80)</span></label>
                        <input class="form-control col-md-3"  value="<?= $BADGE_RSSI_THRESHOLD;?>"  type="number" id="BgRssThrs" name="BgRssThrs" min="20" max="80">
                        <span class="col-md-2"><?php echo $BADGE_RSSI_THRESHOLD_RUNNING ?></span>
                        <span class="col-md-2" style="text-align:center;"><?php echo $m_BADGE_RSSI_THRESHOLD_RUNNING ?></span>
                    </div>
                    <div class="form-group row">
                        <label for="PrvRssThrs" class="col-md-5">Prov RSSI Threshold <span class="limitInfo">(0-31)</span></label>
                        <input class="form-control col-md-3" value="<?= $PROV_RSSI_THRESHOLD;?>" type="number" id="PrvRssThrs" name="PrvRssThrs" min="0" max="31">
                        <span class="col-md-2"><?php echo $PROV_RSSI_THRESHOLD_RUNNING ?></span>
                        <span class="col-md-2" style="text-align:center;"><?php echo $m_PROV_RSSI_THRESHOLD_RUNNING ?></span>
                    </div>
                    <div class="form-group row">
                        <label for="DbgMod" class="col-md-5">Beep On Beacon Lock</label>
                        <select name="DbgMod" id="DbgMod" class="form-control col-md-3" required >
                            <option <?= $DEBUG_BMODE == 1 ? 'selected' : ''; ?> value="1">ON</option>
                            <option <?= $DEBUG_BMODE == 0 ? 'selected' : ''; ?> value="0">OFF</option>
                        </select>
                        <span class="col-md-2"><?php echo $DEBUG_BMODE_RUNNING ?></span>
                        <span class="col-md-2" style="text-align:center;"><?php echo $m_DEBUG_BMODE_RUNNING ?></span>
                    </div>
                    <div class="form-group row">
                        <label for="BpOnRssF" class="col-md-5">Beep On RSSI Fail</label>
                        <select name="BpOnRssF" id="BpOnRssF" class="form-control col-md-3" required >
                            <option <?= $BEEP_ON_RSSIFAIL == 1 ? 'selected' : ''; ?> value="1">ON</option>
                            <option <?= $BEEP_ON_RSSIFAIL == 0 ? 'selected' : ''; ?> value="0">OFF</option>
                        </select>
                        <span class="col-md-2"><?php echo $BEEP_ON_RSSIFAIL_RUNNING ?></span>
                        <span class="col-md-2" style="text-align:center;"><?php echo $m_BEEP_ON_RSSIFAIL_RUNNING ?></span>
                    </div>
                    
                    <div class="form-group row">
                        <label for="BpOnRssP" class="col-md-5">Beep On RSSI Pass</label>
                        <select name="BpOnRssP" id="BpOnRssP" class="form-control col-md-3" required >
                            <option <?= $BEEP_ON_RSSIPASS == 1 ? 'selected' : ''; ?> value="1">ON</option>
                            <option <?= $BEEP_ON_RSSIPASS == 0 ? 'selected' : ''; ?> value="0">OFF</option>
                        </select>
                        <span class="col-md-2"><?php echo $BEEP_ON_RSSIPASS_RUNNING ?></span>
                        <span class="col-md-2" style="text-align:center;"><?php echo $m_BEEP_ON_RSSIPASS_RUNNING ?></span>
                    </div>
                <!-- </div> -->
                <!-- <button class="btn btn-primary hide-advance-settings section-hide">
                    Hide Advanced Settings
                </button> -->
                <?php if(!isset($manager)) : ?>
                    <input type="submit" name="empty_json" value="Delete Settings" class="btn btn-danger delete_settings_btn section-hide">
                <?php endif; ?>
                <?php if(isset($manager)):?>
                <div class="form-group">
					<label for="TIME ZONE">Time Zone</label>
					<select name="zn" id="TIME_ZONE" class="form-control" required >
                        <?php for($i = -11; $i <= 12; $i++) {
                            for($j = 0; $j <= 3; $j++){
                                $m = $j*15;
                                $czn = $i.'.'.sprintf("%02d", $m);
                                $slct = $czn == $zn ? 'selected' : '' ;
                        ?>
                            <option <?= $slct?> > <?= $czn; ?></option>
                        <?php 
                            if($i == 12){
                            break;
                            }
                            }
                        } 
                        ?>
					</select>
                </div>
                <div class="form-group">
					<label for="START TIME">Start Time</label>
					<select name="st" id="START_TIME" class="form-control" required >
                        <?php for($i = 0; $i <= 23; $i++) {
                            $m = 0;
                            for($j = 0; $j <= 11; $j++){
                                $m = $j*5;
                                $cst = $i.'.'.sprintf("%02d", $m);
                                $slct = $cst == $st ? 'selected' : '' ;
                                ?>
                            <option <?= $slct?> > <?php echo $cst; ?></option>
                            <?php 
                            }
                        } 
                        ?>
					</select>
                </div>
                <div class="form-group">
					<label for="START TIME">Stop Time</label>
					<select name="sp" id="STOP_TIME" class="form-control" required >
                        <?php for($i = 0; $i <= 23; $i++) {
                            $m = 0;
                            for($j = 0; $j <= 11; $j++){
                                $m = $j*5;
                                $csp = $i.'.'.sprintf("%02d", $m);
                                $slct = $csp == $sp ? 'selected' : '' ;
                                ?>
                            <option <?= $slct?>> <?php echo $csp; ?></option>
                            <?php 
                            }
                        } 
                        ?>
					</select>
                </div>
                    <?php endif; ?>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<button type="submit" class="btn btn-success">Save</button>
			<a href="<?=base_url($cancelUrl);?>" class="btn btn-warning pull-right">Cancel</a>
		</div>
		</form>
	</div>
	<!-- /.box -->
</section>
<!-- /.content -->

