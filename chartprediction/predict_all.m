function predict_all()
	
	files = dir('data/*.train.csv');
    fileIndex = find(~[files.isdir]);
    
    disp('Processing metros...');
    for i = 1:length(fileIndex)
        if i > 47
            if files(fileIndex(i)).bytes > 0
                fileName = files(fileIndex(i)).name;
                fileName = fileName(:,1:end-10);
                dataName = strcat('data/', strcat(fileName, '.data.csv'));
                trainName = strcat('data/', strcat(fileName, '.train.csv'));
                if exist(dataName, 'file') == 2 && exist(trainName, 'file') == 2
                    disp(fileName);
                    [ranks] = predict(fileName);
                    csvwrite(strcat('data/', strcat(fileName, '.predict.csv')), ranks');
                end
            end
        end
    end 
	
end
