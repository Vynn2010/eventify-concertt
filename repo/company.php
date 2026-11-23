<?php
class Company
{
    public static function findCompanyInfo($conn)
    {
        $result_near = mysqli_query($conn, "SELECT id, title, artist, venue, concert_date, concert_description, img
                                    FROM concerts WHERE concert_date >= NOW()
                                    ORDER BY concert_date ASC LIMIT 1");
        $near = $result_near ? mysqli_fetch_assoc($result_near) : null;
        return $near;
    }
}
?>