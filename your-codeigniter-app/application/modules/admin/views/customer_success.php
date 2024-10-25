<style>
    body {
        text-align: center;
        background: #EBF0F5;
    }
    h1 {
        color: #88B04B;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-weight: 900;
        font-size: 40px;
        margin-bottom: 10px;
    }
    p {
        color: #404F5E;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-size:20px;
        margin: 0;
    }
    .checkmark {
        color: #9ABC66;
        font-size: 100px;
        line-height: 200px;
        margin-left:-15px;
    }
    .card {
        background: white;
        padding: 20px 65px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
    }
</style>
<section class="content col-sm-12">	
    <div class="card">
        <div style="border-radius:200px; height:150px; width:150px; background: #F8FAF5; margin:0 auto;">
            <i class="checkmark">✓</i>
        </div>
        <h1><?= $heading ?></h1> 
        <p><?= $message ?></p>
        <div>
        <a href="<?=base_url($btn_url);?>" class="btn btn-success btn-block pull-right" style="margin-top:15px"><?= $btn_text ?></a>
        </div>
    </div>
</section>