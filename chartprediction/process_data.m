function res = process_data(data)
    res = ~((51-data(:,:)) > 50) .* (51-data(:,:));
end