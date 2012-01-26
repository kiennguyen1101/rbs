<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<div id="main">
    <!--POST PROJECT-->
    <div class="clsInnerpageCommon">

        <h2><?php echo $this->config->item('site_title'); ?> &nbsp;<?php echo $this->lang->line('Search Results'); ?></h2>
        <!-- <h3><span class="clsViewPro"><?php echo $this->lang->line('Search Results'); ?></span></h3> -->
        <form method="post" action="">

            <?php
            //set up table template
            $tmpl = array(
                'table_open' => '<table cellpadding="2" cellspacing="1" width="96%">',
                'heading_row_start' => '<tr class="dt1 dt0">',
                'row_alt_start' => '<tr class="dt2 dt0">',
                'heading_cell_start' => '<th class="dt">',
            );
            $this->table->set_template($tmpl);

            //set default for empty cells
            $this->table->set_empty("&nbsp;");

            //Get Sorting order
            $odr = ($order == 'ASC') ? 'DESC' : 'ASC';

            //create link to sort using project name
            $project_name = "<a href='$base_url&sort=$odr&field=project_name&p=$page'>" . $this->lang->line('Project Name') . "</a>";

            //get table heading
            $heading = array(
                $this->lang->line('SI.No'),
                $project_name,
            );

            //Get Customize data fields
            if ($this->session->userdata('show_cat')) {
                array_push($heading, $this->lang->line('Job Type'));
            }

            if ($this->session->userdata('show_status')) {
                array_push($heading, $this->lang->line('Status'));
            }

            if ($this->session->userdata('show_bids')) {
                array_push($heading, $this->lang->line('Bids'));
            }

            if ($this->session->userdata('show_date')) {
                array_push($heading, $this->lang->line('Start Date'));
            }

            //set table headings
            $this->table->set_heading($heading);

            if (isset($projects) && $projects->num_rows() > 0) {
                $i = 0;
                foreach ($projects->result() as $project) {

                    $t_row = array();
                    $i = $i + 1;
                    array_push($t_row, $i);

                    $prj_name = sprintf('<a href="%s">%s</a>', site_url('project/view/' . $project->id), highlight_phrase($project->project_name, $keyword, '<b>', '</b>'));

                    array_push($t_row, $prj_name);

                    if ($this->session->userdata('show_cat'))
                        array_push($t_row, getCategoryLinks($project->project_categories));


                    if ($this->session->userdata('show_status')) {
                        $status = getProjectStatus($project->project_status);
                        if ($status != "Open")
                            $status = "<span style='color:red;'>$status</span>";
                        else
                            $status = "<span style='color:green;'>$status</span>";
                        array_push($t_row, $status);
                    }


                    if ($this->session->userdata('show_bids'))
                        array_push($t_row, getNumBid($project->id));

                    if ($this->session->userdata('show_date'))
                        array_push($t_row, get_date($project->created));
                    
                    $this->table->add_row($t_row);
                }
            }
            else {
                $cell = array('data' => $this->lang->line('No Records'), 'class' => 'help', 'colspan' => count($heading));
                $this->table->add_row($cell);
            }

            //print out the table
            echo $this->table->generate();
            ?>

        </form>
        <!--PAGING-->
<?php if (isset($pagination))
    echo $pagination; ?>
        <!--END OF PAGING-->

    </div>
    <!--END OF POST PROJECT-->
</div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>