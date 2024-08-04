<table>
    <thead>
    <td><b>Email : </b></td>
    <td><b>Date Added : </b></td>
    </thead>
        <?php if(isset($newsletters) && !empty($newsletters)){ ?>
    <tbody>
        <?php

        //$storage = Storage::disk('public');

        //$img_path = 'products/';
        //$thumb_path = $img_path.'thumb/';

            foreach($newsletters as $newsletter){
                $email = (isset($newsletter->email))?$newsletter->email:'';
                $created_at = (isset($newsletter->created_at))?$newsletter->created_at:'';
                // $created_at = CustomHelper::DateFormat($created_at, 'd/m/Y');
                //$productInventory = $product->productInventory;

                //prd($product->toArray());

                //if(!empty($productInventory)){

                    //foreach($productInventory as $pi){

                        ?>
                        <tr>
                            <td>{{$newsletter->email}}</td>
                            <td>{{ CustomHelper::DateFormat($newsletter->created_at, 'd/m/Y') }}</td>
                        </tr>

                        <?php
                    //}
                //}
            }
        }
        ?>
    </tbody>
</table>