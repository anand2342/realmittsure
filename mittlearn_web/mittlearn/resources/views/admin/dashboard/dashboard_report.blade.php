<table>
    <thead>
        <tr style="background-color: yellow">
            <th>Metric</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Total Schools</td>
            <td>{{ $data['totalSchools'] }}</td>
        </tr>
        <tr>
            <td>Verified Schools</td>
            <td>{{ $data['verifiedSchools'] }}</td>
        </tr>
        <tr>
            <td>Unverified Schools</td>
            <td>{{ $data['unVerifiedSchools'] }}</td>
        </tr>
        <tr>
            <td>Total Users</td>
            <td>{{ $data['totalUsers'] }}</td>
        </tr>
        <tr>
            <td>Total Revenue</td>
            <td>{{ number_format($data['totalRevenue'], 2) }}</td>
        </tr>
        <tr>
            <td>Total Subscriptions</td>
            <td>{{ $data['totalSubscriptions'] }}</td>
        </tr>
    </tbody>
</table>
